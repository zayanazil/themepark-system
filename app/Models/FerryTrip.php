<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerryTrip extends Model
{
    use HasFactory;

    protected $fillable = ['route_name', 'departure_time', 'capacity'];

    // Who is on this boat?
    public function tickets() {
        return $this->hasMany(FerryTicket::class);
    }
}
