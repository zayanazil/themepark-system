<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPromotion extends Model
{
    use HasFactory;
    
    protected $fillable = ['event_id', 'title', 'discount_percent', 'description'];
    
    public function event()
    {
        return $this->belongsTo(ThemeParkEvent::class, 'event_id');
    }
    
    // Apply discount to a price
    public function applyDiscount($price)
    {
        $discount = $price * ($this->discount_percent / 100);
        return round($price - $discount, 2);
    }
}