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
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'carId')) {
                try {
                    $table->dropForeign(['carId']);
                } catch (\Exception $e) {
                    // Ignore if constraint doesn't exist
                }
                $table->dropColumn('carId');
            }
        });

        Schema::dropIfExists('cars');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
            $table->softDeletes();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('carId')->nullable()->constrained('cars')->nullOnDelete();
        });
    }
};
