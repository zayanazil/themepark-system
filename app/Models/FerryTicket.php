<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hotel_booking_id',
        'ferry_time',
        'status'
    ];

    public function hotelBooking() {
        return $this->belongsTo(HotelBooking::class);
    }
}
