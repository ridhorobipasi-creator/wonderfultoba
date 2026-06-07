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
        Schema::table('packages', function (Blueprint $table) {
            $table->index(['status', 'isOutbound']);
            $table->index('slug');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('status');
            $table->index('packageId');
            $table->index('createdAt');
            $table->index('bookingCode');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->index(['status', 'createdAt']);
            $table->index('slug');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['status', 'isOutbound']);
            $table->dropIndex(['slug']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['packageId']);
            $table->dropIndex(['createdAt']);
            $table->dropIndex(['bookingCode']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['status', 'createdAt']);
            $table->dropIndex(['slug']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex(['key']);
        });
    }
};
