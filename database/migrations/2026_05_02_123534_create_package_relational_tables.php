<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create package_images table
        Schema::create('package_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Create package_amenities table (includes/excludes)
        Schema::create('package_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['include', 'exclude']);
            $table->timestamps();
        });

        // 3. Migrate Data
        $packages = DB::table('packages')->get();
        foreach ($packages as $package) {
            // Images
            $images = json_decode($package->images, true);
            if (is_array($images)) {
                foreach ($images as $index => $image) {
                    DB::table('package_images')->insert([
                        'package_id' => $package->id,
                        'image_path' => $image,
                        'sort_order' => $index,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Includes
            $includes = json_decode($package->includes, true);
            if (is_array($includes)) {
                foreach ($includes as $item) {
                    DB::table('package_amenities')->insert([
                        'package_id' => $package->id,
                        'name' => $item,
                        'type' => 'include',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Excludes
            $excludes = json_decode($package->excludes, true);
            if (is_array($excludes)) {
                foreach ($excludes as $item) {
                    DB::table('package_amenities')->insert([
                        'package_id' => $package->id,
                        'name' => $item,
                        'type' => 'exclude',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_amenities');
        Schema::dropIfExists('package_images');
    }
};
