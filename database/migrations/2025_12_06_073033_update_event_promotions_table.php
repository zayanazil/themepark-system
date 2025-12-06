<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('event_promotions', function (Blueprint $table) {
            // Remove old column
            $table->dropColumn('discount_details');
            
            // Add new columns
            $table->integer('discount_percent')->after('title');
            $table->text('description')->nullable()->after('discount_percent');
        });
    }

    public function down()
    {
        Schema::table('event_promotions', function (Blueprint $table) {
            $table->dropColumn(['discount_percent', 'description']);
            $table->string('discount_details');
        });
    }
};