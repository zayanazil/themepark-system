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
        // 1. Update Events Table (Add Category)
        Schema::table('theme_park_events', function (Blueprint $table) {
            $table->enum('category', ['Ride', 'Show', 'Beach Event', 'General'])->default('General');
        });

        // 2. Update Bookings Table (Add Status for Validation)
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->enum('status', ['valid', 'redeemed', 'cancelled'])->default('valid');
        });

        // 3. Create Event Promotions Table
        Schema::create('event_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('theme_park_events')->onDelete('cascade');
            $table->string('title');
            $table->string('discount_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
