<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeParkEvent extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'category', 'capacity', 'event_date', 'event_time', 'price'];

    public function bookings()
    {
        return $this->hasMany(EventBooking::class, 'event_id');
    }

    // Change to hasOne since we want one promotion per event
    public function promotion()
    {
        return $this->hasOne(EventPromotion::class, 'event_id');
    }

    // Get remaining capacity
    public function getRemainingCapacityAttribute()
    {
        $bookedTickets = $this->bookings()->sum('tickets');
        return $this->capacity - $bookedTickets;
    }

    // Check if event is sold out
    public function isSoldOut()
    {
        return $this->remaining_capacity <= 0;
    }
}