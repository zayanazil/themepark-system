<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'tickets',
        'total_price'
    ];

    public function event() {
        return $this->belongsTo(ThemeParkEvent::class, 'event_id');
    }
}

