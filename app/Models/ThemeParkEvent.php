<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeParkEvent extends Model
{
    use HasFactory;

    // Added fillable so we can seed data later
    protected $fillable = ['name', 'description', 'capacity', 'event_date', 'event_time', 'price'];

    public function bookings() {
        return $this->hasMany(EventBooking::class, 'event_id');
    }
}
