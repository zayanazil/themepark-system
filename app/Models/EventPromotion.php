<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPromotion extends Model
{
    use HasFactory;
    protected $fillable = ['event_id', 'title', 'discount_details'];

    public function event() { return $this->belongsTo(ThemeParkEvent::class); }
}
