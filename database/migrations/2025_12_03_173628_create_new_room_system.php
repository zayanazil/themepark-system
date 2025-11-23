<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Clean up old tables if they exist
        Schema::dropIfExists('ferry_tickets');
        Schema::dropIfExists('hotel_bookings');
        Schema::dropIfExists('room_types'); // We are deleting this concept
        Schema::dropIfExists('rooms');

        // 2. Create 'rooms' table (Individual Rooms)
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('room_number'); // e.g. "101"
            $table->enum('type', ['Single', 'Couple', 'Family', 'Deluxe']);
            $table->decimal('price', 8, 2);
            $table->boolean('is_available')->default(true); // For maintenance
            $table->timestamps();
        });

        // 3. Re-create Bookings Table (Updated to use String Type)
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('hotel_id')->constrained();
            $table->string('room_type'); // We store "Single", "Deluxe", etc.
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guests');
            $table->enum('status', ['pending','confirmed','cancelled'])->default('pending');
            $table->timestamps();
        });

        // 4. Re-create Ferry Tickets (since we dropped it to save bookings)
        Schema::create('ferry_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('hotel_booking_id')->constrained('hotel_bookings')->onDelete('cascade');
            $table->string('ferry_time');
            $table->enum('status', ['valid','cancelled'])->default('valid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop everything
        Schema::dropIfExists('ferry_tickets');
        Schema::dropIfExists('hotel_bookings');
        Schema::dropIfExists('rooms');
    }
};
