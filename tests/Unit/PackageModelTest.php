<?php

namespace Tests\Unit;

use App\Models\City;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_can_be_created()
    {
        $package = Package::create([
            'slug' => 'test-package',
            'name' => 'Test Package',
            'description' => 'Test Description',
            'price' => 1000000,
            'duration' => '3D2N',
            'images' => ['image1.jpg', 'image2.jpg'],
            'includes' => ['Hotel', 'Transport'],
            'excludes' => ['Flight'],
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('packages', [
            'slug' => 'test-package',
            'name' => 'Test Package',
        ]);

        $this->assertEquals('test-package', $package->slug);
        $this->assertEquals(1000000, $package->price);
    }

    public function test_package_images_are_cast_to_array()
    {
        $package = Package::create([
            'slug' => 'test-package',
            'name' => 'Test Package',
            'description' => 'Test Description',
            'price' => 1000000,
            'duration' => '3D2N',
            'images' => ['image1.jpg', 'image2.jpg'],
            'includes' => ['Hotel'],
            'excludes' => ['Flight'],
        ]);

        $this->assertIsArray($package->images);
        $this->assertCount(2, $package->images);
        $this->assertEquals('image1.jpg', $package->images[0]);
    }

    public function test_package_belongs_to_city()
    {
        $city = City::create([
            'name' => 'Medan',
            'slug' => 'medan',
            'type' => 'domestic',
            'country' => 'Indonesia',
        ]);

        $package = Package::create([
            'slug' => 'test-package',
            'name' => 'Test Package',
            'description' => 'Test Description',
            'price' => 1000000,
            'duration' => '3D2N',
            'images' => ['image1.jpg'],
            'includes' => ['Hotel'],
            'excludes' => ['Flight'],
            'cityId' => $city->id,
        ]);

        $this->assertInstanceOf(City::class, $package->city);
        $this->assertEquals('Medan', $package->city->name);
    }
}
