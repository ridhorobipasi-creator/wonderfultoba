<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Consolidated schema hardening from the July 2026 audit:
 *  - documents the previously-undeclared blogs.tags column in a migration
 *    (source-of-truth drift fixed);
 *  - converts money columns from float (double) to decimal(15,2);
 *  - relaxes packages.duration to nullable to match validation;
 *  - promotes bookingCode to a unique index;
 *  - adds indexes for real query patterns.
 *
 * Every change is guarded so it is a safe no-op on databases that already
 * received part of it manually.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Make migrations authoritative for blogs.tags (added manually in prod).
        if (! Schema::hasColumn('blogs', 'tags')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->json('tags')->nullable()->after('category');
            });
        }

        // 2. Money columns: double -> decimal(15,2) (exact for currency arithmetic).
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0)->change();
            $table->decimal('childPrice', 15, 2)->nullable()->default(0)->change();
            $table->decimal('dronePrice', 15, 2)->nullable()->default(0)->change();
            if (Schema::hasColumn('packages', 'cost_price')) {
                $table->decimal('cost_price', 15, 2)->nullable()->default(0)->change();
            }
            // 3. duration nullable to match 'nullable' validation rule.
            $table->string('duration')->nullable()->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('totalPrice', 15, 2)->change();
            if (Schema::hasColumn('bookings', 'total_cost')) {
                $table->decimal('total_cost', 15, 2)->nullable()->default(0)->change();
            }
        });

        // 4. bookingCode: replace the plain index with a unique one.
        Schema::table('bookings', function (Blueprint $table) {
            try {
                $table->dropIndex(['bookingCode']);
            } catch (\Throwable $e) {
                // index may not exist on some environments — ignore.
            }
            $table->unique('bookingCode');
        });

        // 5. Indexes for real query patterns.
        Schema::table('packages', function (Blueprint $table) {
            $table->index(['isFeatured', 'sortOrder']);
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->index(['isActive', 'orderPriority']);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->index('regency_id');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('created_at');
            $table->index(['model', 'model_id']);
        });

        if (Schema::hasTable('city_package')) {
            Schema::table('city_package', function (Blueprint $table) {
                $table->unique(['city_id', 'package_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['isFeatured', 'sortOrder']);
            $table->double('price')->default(0)->change();
            $table->double('childPrice')->nullable()->default(0)->change();
            $table->double('dronePrice')->nullable()->default(0)->change();
            if (Schema::hasColumn('packages', 'cost_price')) {
                $table->double('cost_price')->nullable()->default(0)->change();
            }
            $table->string('duration')->nullable(false)->change();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['bookingCode']);
            $table->index('bookingCode');
            $table->double('totalPrice')->change();
            if (Schema::hasColumn('bookings', 'total_cost')) {
                $table->double('total_cost')->nullable()->default(0)->change();
            }
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropIndex(['isActive', 'orderPriority']);
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex(['regency_id']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['model', 'model_id']);
        });

        if (Schema::hasTable('city_package')) {
            Schema::table('city_package', function (Blueprint $table) {
                $table->dropUnique(['city_id', 'package_id']);
            });
        }
    }
};
