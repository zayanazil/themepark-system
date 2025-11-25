<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeParkEvent extends Model
{
    use HasFactory;
    // Added 'category'
    protected $fillable = ['name', 'description', 'category', 'capacity', 'event_date', 'event_time', 'price'];

    public function bookings() {
        return $this->hasMany(EventBooking::class, 'event_id');
    }

    public function promotions() {
        return $this->hasMany(EventPromotion::class, 'event_id');
    }
}
