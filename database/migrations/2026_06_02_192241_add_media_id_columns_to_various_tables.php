<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add cover_image_id to blogs table (use 'image' column as reference)
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('cover_image_id')->nullable()->after('image')->constrained('media')->onDelete('set null');
        });

        // Add image_id to cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('image_id')->nullable()->after('image')->constrained('media')->onDelete('set null');
        });

        // Add logo_id to clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('logo_id')->nullable()->after('logo')->constrained('media')->onDelete('set null');
        });

        // Add image_id to gallery_images table  
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->foreignId('image_id')->nullable()->after('imageUrl')->constrained('media')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cover_image_id');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('image_id');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('logo_id');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('image_id');
        });
    }
};