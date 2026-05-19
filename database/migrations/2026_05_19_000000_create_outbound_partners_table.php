<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbound_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('logo', 500);
            $table->string('websiteUrl', 500)->nullable();
            $table->integer('orderPriority')->default(0);
            $table->boolean('isActive')->default(true);
            $table->dateTime('createdAt')->useCurrent();
            $table->dateTime('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbound_partners');
    }
};
