<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory;
    // Updated fillable to use 'room_type' string
    protected $fillable = [
    'user_id', 'hotel_id', 'room_type',
    'check_in', 'check_out', 'guests',
    'status', 'total_price'
];

    public function user() { return $this->belongsTo(User::class); }
    public function hotel() { return $this->belongsTo(Hotel::class); }
}
