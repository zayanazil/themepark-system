<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPromotion extends Model
{
    use HasFactory;

    protected $fillable = ['hotel_id', 'title', 'discount_percent', 'description'];

    public function hotel() {
        return $this->belongsTo(Hotel::class);
    }
}
