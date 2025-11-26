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
    // 1. Create Schedules Table
    Schema::create('ferry_trips', function (Blueprint $table) {
        $table->id();
        $table->string('route_name'); // e.g., "Male' -> Resort"
        $table->dateTime('departure_time');
        $table->integer('capacity');
        $table->timestamps();
    });

    // 2. Update Tickets Table (Link to Trip instead of string time)
    // We drop the old table to restart cleanly
    Schema::dropIfExists('ferry_tickets');

    Schema::create('ferry_tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        // Link to the specific boat trip
        $table->foreignId('ferry_trip_id')->constrained()->onDelete('cascade');
        // Still need to link to hotel booking for validation
        $table->foreignId('hotel_booking_id')->constrained('hotel_bookings');
        $table->enum('status', ['valid', 'boarded', 'cancelled'])->default('valid');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferry_schedule_system');
    }
};
