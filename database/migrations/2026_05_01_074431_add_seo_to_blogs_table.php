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
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('metaTitle')->nullable()->after('status');
            $table->text('metaDescription')->nullable()->after('metaTitle');
            // Make excerpt and author more flexible if they aren't already
            // In SQLite we can't easily change columns to nullable, but we can add them if missing.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['metaTitle', 'metaDescription']);
        });
    }
};
