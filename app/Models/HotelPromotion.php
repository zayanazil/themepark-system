<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPromotion extends Model
{
    use HasFactory;

    protected $fillable = ['hotel_id', 'title', 'discount_percent', 'description'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    // Get discount as numeric value (e.g., "15%" returns 15)
    public function getDiscountValueAttribute()
    {
        return (int) preg_replace('/[^0-9]/', '', $this->discount_percent);
    }

    // Apply discount to a price
    public function applyDiscount($price)
    {
        $discountValue = $this->discount_value;
        return $price - ($price * ($discountValue / 100));
    }
}