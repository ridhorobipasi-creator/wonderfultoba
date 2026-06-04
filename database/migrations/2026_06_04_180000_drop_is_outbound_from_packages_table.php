<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('packages', 'isOutbound')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('isOutbound');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('packages', 'isOutbound')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->boolean('isOutbound')->default(false)->after('isFeatured');
            });
        }
    }
};
