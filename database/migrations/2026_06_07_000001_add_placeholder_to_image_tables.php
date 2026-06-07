<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom placeholder menyimpan LQIP (Low Quality Image Placeholder) berupa
 * data-URI base64 mungil untuk efek blur-up saat gambar asli dimuat.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['package_images', 'gallery_images', 'media'] as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'placeholder')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->text('placeholder')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['package_images', 'gallery_images', 'media'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'placeholder')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('placeholder');
                });
            }
        }
    }
};
