<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryTicket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ferry_trip_id', 'hotel_booking_id', 'status'];

    public function trip() {
        return $this->belongsTo(FerryTrip::class, 'ferry_trip_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function hotelBooking() {
        return $this->belongsTo(HotelBooking::class);
    }
}
