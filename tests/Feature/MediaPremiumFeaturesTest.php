<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaPremiumFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user matching role requirements
        $this->admin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'admin@test.com',
        ]);
    }

    /**
     * Helper to create a true GD-compatible JPEG file.
     */
    protected function createFakeJpeg($width = 100, $height = 100)
    {
        $image = imagecreatetruecolor($width, $height);
        // Fill with a specific color (red)
        $color = imagecolorallocate($image, 255, 0, 0);
        imagefill($image, 0, 0, $color);

        ob_start();
        imagejpeg($image, null, 100);
        $data = ob_get_clean();
        imagedestroy($image);

        $tempFile = tempnam(sys_get_temp_dir(), 'test_img');
        file_put_contents($tempFile, $data);

        return new UploadedFile($tempFile, 'red_image.jpg', 'image/jpeg', null, true);
    }

    public function test_it_uploads_image_converts_to_webp_calculates_dominant_color_and_auto_alt_text()
    {
        Storage::fake('public');

        $file = $this->createFakeJpeg();

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.store'), [
            'files' => [$file],
            'category' => 'tours',
            'watermark' => '0',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Verify the database has the new media record
        $this->assertDatabaseHas('media', [
            'category' => 'tours',
            'mime_type' => 'image/webp',
            'dominant_color' => '#fe0000', // Our solid red fake image
        ]);

        $media = Media::first();
        $this->assertNotNull($media->dominant_color);
        $this->assertStringStartsWith('#', $media->dominant_color);
        $this->assertStringContainsString('Foto Red Image - Kategori Tours', $media->alt_text);

        // Verify that the files exist in public storage in WebP format
        Storage::disk('public')->assertExists($media->path);
        Storage::disk('public')->assertExists($media->thumb);
        $this->assertStringEndsWith('.webp', $media->path);
    }

    public function test_it_applies_watermark_when_selected()
    {
        Storage::fake('public');

        $file = $this->createFakeJpeg(500, 500); // larger image to fit the watermark

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.store'), [
            'files' => [$file],
            'category' => 'packages',
            'watermark' => '1',
        ]);

        $response->assertStatus(200);
        $media = Media::first();

        Storage::disk('public')->assertExists($media->path);

        // The file should be saved correctly in webp format
        $this->assertStringEndsWith('.webp', $media->path);
    }

    public function test_it_syncs_new_physical_files_from_disk_and_populates_dominant_colors()
    {
        Storage::fake('public');

        // Create a physical file directly on the disk
        $file = $this->createFakeJpeg(10, 10);
        Storage::disk('public')->put('gallery/tours/outside_file.jpg', file_get_contents($file->getRealPath()));

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.sync'));
        $response->assertStatus(200);

        $this->assertDatabaseHas('media', [
            'path' => 'gallery/tours/outside_file.jpg',
            'category' => 'tours',
        ]);

        $media = Media::where('path', 'gallery/tours/outside_file.jpg')->first();
        $this->assertNotNull($media->dominant_color);
    }

    public function test_it_can_convert_all_non_webp_images_to_webp_format()
    {
        Storage::fake('public');

        // Create a non-webp database entry and disk file
        $file = $this->createFakeJpeg(10, 10);
        Storage::disk('public')->put('gallery/tours/convert_me.jpg', file_get_contents($file->getRealPath()));

        $media = Media::create([
            'filename' => 'convert_me.jpg',
            'original_name' => 'convert_me.jpg',
            'path' => 'gallery/tours/convert_me.jpg',
            'category' => 'tours',
            'mime_type' => 'image/jpeg',
            'size' => 1000,
            'thumb' => 'gallery/tours/thumbnails/convert_me.jpg',
            'dominant_color' => null,
        ]);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.convert-all'));
        $response->assertStatus(200);

        // Verify the database record updated to webp
        $media->refresh();
        $this->assertStringEndsWith('.webp', $media->path);
        $this->assertEquals('image/webp', $media->mime_type);
        $this->assertNotNull($media->dominant_color);

        // Verify old file deleted and new WebP exists
        Storage::disk('public')->assertMissing('gallery/tours/convert_me.jpg');
        Storage::disk('public')->assertExists($media->path);
        Storage::disk('public')->assertExists($media->thumb);
    }

    public function test_it_audits_storage_and_detects_physical_orphan_files()
    {
        Storage::fake('public');

        // 1. Registered file
        $registeredFile = $this->createFakeJpeg(5, 5);
        Storage::disk('public')->put('gallery/tours/registered.webp', file_get_contents($registeredFile->getRealPath()));
        Media::create([
            'filename' => 'registered.webp',
            'original_name' => 'registered.webp',
            'path' => 'gallery/tours/registered.webp',
            'category' => 'tours',
            'mime_type' => 'image/webp',
            'size' => 100,
            'thumb' => 'gallery/tours/thumbnails/registered.webp',
        ]);

        // 2. Orphan file on disk (not registered in database)
        $orphanFile = $this->createFakeJpeg(5, 5);
        Storage::disk('public')->put('gallery/tours/orphan.webp', file_get_contents($orphanFile->getRealPath()));

        $response = $this->actingAs($this->admin)->getJson(route('admin.media.audit'));
        $response->assertStatus(200);

        // It should identify the orphan file
        $response->assertJsonFragment([
            'path' => 'gallery/tours/orphan.webp',
            'filename' => 'orphan.webp',
        ]);

        // And should NOT list registered file as orphan
        $orphans = $response->json('orphans');
        $paths = collect($orphans)->pluck('path');
        $this->assertNotContains('gallery/tours/registered.webp', $paths);
    }

    public function test_it_cleans_orphan_files_from_disk()
    {
        Storage::fake('public');

        $orphanFile = $this->createFakeJpeg(5, 5);
        Storage::disk('public')->put('gallery/tours/orphan.webp', file_get_contents($orphanFile->getRealPath()));
        Storage::disk('public')->put('gallery/tours/thumbnails/orphan.webp', file_get_contents($orphanFile->getRealPath()));

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.clean-orphans'), [
            'paths' => ['gallery/tours/orphan.webp'],
        ]);

        $response->assertStatus(200);

        // Assert files are completely deleted
        Storage::disk('public')->assertMissing('gallery/tours/orphan.webp');
        Storage::disk('public')->assertMissing('gallery/tours/thumbnails/orphan.webp');
    }

    public function test_it_crops_an_image_using_base64_webp_payload()
    {
        Storage::fake('public');

        // Create a media record
        $file = $this->createFakeJpeg(200, 200);
        Storage::disk('public')->put('gallery/tours/to_crop.webp', file_get_contents($file->getRealPath()));

        $media = Media::create([
            'filename' => 'to_crop.webp',
            'original_name' => 'to_crop.webp',
            'path' => 'gallery/tours/to_crop.webp',
            'category' => 'tours',
            'mime_type' => 'image/webp',
            'size' => 1000,
            'thumb' => 'gallery/tours/thumbnails/to_crop.webp',
            'dominant_color' => '#ffffff',
        ]);

        // Generate base64 data url of a cropped blue image
        $image = imagecreatetruecolor(50, 50);
        $color = imagecolorallocate($image, 0, 0, 255); // blue
        imagefill($image, 0, 0, $color);
        ob_start();
        imagewebp($image, null, 100);
        $data = ob_get_clean();
        imagedestroy($image);
        $dataUrl = 'data:image/webp;base64,'.base64_encode($data);

        $response = $this->actingAs($this->admin)->postJson(route('admin.media.crop', $media->id), [
            'image' => $dataUrl,
        ]);

        $response->assertStatus(200);

        // Verify database and file has updated properties (size, color, disk files)
        $media->refresh();
        $this->assertEquals('#0000ff', $media->dominant_color); // Our blue cropped image color
        Storage::disk('public')->assertExists($media->path);
        Storage::disk('public')->assertExists($media->thumb);
    }
}
