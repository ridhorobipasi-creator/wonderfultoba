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
        if (!Schema::hasColumn('packages', 'cost_price')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->double('cost_price')->nullable()->default(0)->after('price');
            });
        }

        if (!Schema::hasColumn('bookings', 'total_cost')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->double('total_cost')->nullable()->default(0)->after('totalPrice');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['cost_price']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['total_cost']);
        });
    }
};
