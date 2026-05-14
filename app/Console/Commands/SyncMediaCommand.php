<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncMediaCommand extends Command
{
    protected $signature = 'media:sync {--force : Force overwrite existing entries}';
    protected $description = 'Smart sync all physical storage files to Media and Gallery tables (Tour & Outbound)';

    public function handle()
    {
        $this->info('🚀 Memulai sinkronisasi media cerdas...');
        
        $files = Storage::disk('public')->allFiles();
        $mediaSynced = 0;
        $gallerySynced = 0;

        foreach ($files as $file) {
            // Filter: Hanya gambar dan dokumen umum
            if (!Str::contains($file, ['.jpg', '.jpeg', '.png', '.webp', '.svg', '.pdf'])) continue;
            // Lewati folder internal/temp
            if (Str::contains($file, ['thumbnails/', '.gitignore', 'framework/', 'sessions/'])) continue;

            $filename = basename($file);
            $path = $file;

            // 1. Cek/Simpan ke Tabel Media (Pusat Media)
            $media = Media::where('path', $path)->first();
            if (!$media) {
                $media = Media::create([
                    'filename' => $filename,
                    'original_name' => $filename,
                    'path' => $path,
                    'category' => $this->detectCategory($path),
                    'mime_type' => $this->getMime($path),
                    'size' => Storage::disk('public')->size($path),
                ]);
                $mediaSynced++;
            }

            // 2. Cek/Simpan ke Tabel GalleryImage (Jika relevan)
            // Kriteria: Jika berada di folder gallery, atau mengandung keyword tour/outbound
            if ($this->shouldGoToGallery($path)) {
                $existsInGallery = GalleryImage::where('imageUrl', $path)->exists();
                if (!$existsInGallery) {
                    GalleryImage::create([
                        'caption' => Str::title(str_replace(['-', '_', '.webp', '.jpg', '.png'], ' ', $filename)),
                        'category' => $this->detectGalleryCategory($path),
                        'imageUrl' => $path,
                        'isActive' => true,
                    ]);
                    $gallerySynced++;
                    $this->line("✅ Gallery Added: {$filename} (" . $this->detectGalleryCategory($path) . ")");
                }
            }
        }

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("🎉 Sinkronisasi Selesai!");
        $this->info("📍 Media Baru Terdaftar: {$mediaSynced}");
        $this->info("🖼️ Galeri Baru Terdaftar: {$gallerySynced}");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }

    private function detectCategory($path)
    {
        if (Str::contains($path, 'branding')) return 'branding';
        if (Str::contains($path, 'cms')) return 'cms';
        if (Str::contains($path, 'gallery')) return 'gallery';
        if (Str::contains($path, 'tour')) return 'tour';
        if (Str::contains($path, 'outbound')) return 'outbound';
        return 'other';
    }

    private function shouldGoToGallery($path)
    {
        // Masukkan ke galeri jika di folder gallery atau mengandung kata kunci kegiatan
        $keywords = ['outbound', 'tour', 'kegiatan', 'wisata', 'gallery', '2023', '2026'];
        return Str::contains(Str::lower($path), $keywords) && !Str::contains($path, ['branding', 'icons', 'cms']);
    }

    private function detectGalleryCategory($path)
    {
        $pathLower = Str::lower($path);
        if (Str::contains($pathLower, 'outbound')) return 'outbound';
        if (Str::contains($pathLower, 'tour')) return 'tour';
        
        // Default berdasarkan folder atau tahun
        return 'tour'; 
    }

    private function getMime($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return match(Str::lower($ext)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
            default => 'application/octet-stream'
        };
    }
}
