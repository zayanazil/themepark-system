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
        // 1. Create Room Types Table
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g. "Deluxe Suite"
            $table->integer('total_quantity'); // e.g. 10 rooms of this type exist
            $table->decimal('price_per_night', 8, 2);
            $table->timestamps();
        });

        // 2. Modify Hotels Table (Remove simple room count)
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('room_count');
        });

        // 3. Modify Bookings Table (Link to Room Type instead of just Hotel)
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreignId('room_type_id')->nullable()->constrained('room_types');
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
