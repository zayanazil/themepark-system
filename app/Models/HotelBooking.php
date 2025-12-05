<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'hotel_id',
        'room_id',      // Add this line!
        'room_type',
        'check_in',
        'check_out',
        'guests',
        'status',
        'total_price'
    ];
    
    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function hotel() { 
        return $this->belongsTo(Hotel::class); 
    }
    
    public function room() { 
        return $this->belongsTo(Room::class); 
    }
}