<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->decimal('total_price', 8, 2)->change();
        });
    }
};