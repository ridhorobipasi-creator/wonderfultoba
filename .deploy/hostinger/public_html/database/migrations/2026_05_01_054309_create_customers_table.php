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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->timestamp('last_booking_at')->nullable();
            $table->timestamps();
        });

        // Add customer_id to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('customerId')->nullable()->constrained('customers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['customerId']);
            $table->dropColumn('customerId');
        });
        Schema::dropIfExists('customers');
    }
};
