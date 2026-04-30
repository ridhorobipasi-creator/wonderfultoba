<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 2. CITIES
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('domestic');
            $table->string('country')->default('Indonesia');
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('place')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 3. PACKAGES (Tour Packages)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('shortDescription')->nullable();
            $table->longText('description');
            $table->string('locationTag')->nullable();
            $table->double('price')->default(0);
            $table->double('childPrice')->nullable()->default(0);
            $table->string('priceDisplay')->nullable();
            $table->string('duration');
            $table->json('images'); // Array of strings
            $table->json('includes');
            $table->json('excludes');
            $table->json('pricingDetails')->nullable();
            $table->json('itinerary')->nullable();
            $table->longText('itineraryText')->nullable();
            $table->double('dronePrice')->nullable()->default(0);
            $table->string('droneLocation')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->boolean('isFeatured')->default(false);
            $table->boolean('isOutbound')->default(false);
            $table->integer('sortOrder')->default(0);
            $table->string('metaTitle')->nullable();
            $table->text('metaDescription')->nullable();
            $table->json('translations')->nullable();
            $table->foreignId('cityId')->nullable()->constrained('cities')->nullOnDelete();
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 4. CARS
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->integer('capacity');
            $table->string('transmission');
            $table->string('fuel');
            $table->double('price')->default(0);
            $table->double('priceWithDriver')->nullable()->default(0);
            $table->json('images');
            $table->longText('description')->nullable();
            $table->text('terms')->nullable();
            $table->json('features')->nullable();
            $table->json('includes')->nullable();
            $table->string('status')->default('available');
            $table->boolean('isFeatured')->default(false);
            $table->integer('sortOrder')->default(0);
            $table->string('metaTitle')->nullable();
            $table->text('metaDescription')->nullable();
            $table->json('pricingDetails')->nullable();
            $table->json('translations')->nullable();
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 5. BOOKINGS
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type'); // package | car
            $table->foreignId('packageId')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('carId')->nullable()->constrained('cars')->nullOnDelete();
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->double('totalPrice');
            $table->string('customerName');
            $table->string('customerEmail');
            $table->string('customerPhone');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 6. BLOGS
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('content');
            $table->text('excerpt');
            $table->string('image')->nullable();
            $table->string('author');
            $table->string('category')->default('tour');
            $table->string('status')->default('published');
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 7. SETTINGS
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 8. OUTBOUND SERVICES
        Schema::create('outbound_services', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('shortDesc', 200)->nullable();
            $table->text('detailDesc')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('image', 500)->nullable();
            $table->integer('orderPriority')->default(0);
            $table->boolean('isActive')->default(true);
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 9. CLIENTS
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('logo', 500)->nullable();
            $table->string('websiteUrl', 500)->nullable();
            $table->integer('orderPriority')->default(0);
            $table->boolean('isActive')->default(true);
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 10. GALLERY IMAGES
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('imageUrl', 500);
            $table->text('caption')->nullable();
            $table->string('category', 50)->nullable();
            $table->json('tags')->nullable();
            $table->dateTime('eventDate')->nullable();
            $table->integer('orderPriority')->default(0);
            $table->boolean('isActive')->default(true);
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 11. OUTBOUND VIDEOS
        Schema::create('outbound_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('youtubeUrl');
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 12. OUTBOUND LOCATIONS
        Schema::create('outbound_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        // 13. PACKAGE TIERS
        Schema::create('package_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('tierName');
            $table->string('category')->default('general');
            $table->text('tagline')->nullable();
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('outbound_services');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('cities');
    }
};
