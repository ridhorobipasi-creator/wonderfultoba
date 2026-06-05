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
        // For simple string key/value settings
        \Illuminate\Support\Facades\DB::table('settings')
            ->whereIn('key', ['wa_number', 'whatsapp', 'contact_wa_1', 'contact_wa'])
            ->update(['key' => 'contact_whatsapp']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
