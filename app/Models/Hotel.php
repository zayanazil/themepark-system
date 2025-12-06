<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function rooms() {
        return $this->hasMany(Room::class);
    }

    public function getAvailableRoomsByType($checkIn, $checkOut)
    {
        // Get all room IDs that are booked during these dates
        $bookedRoomIds = HotelBooking::where('hotel_id', $this->id)
            ->where('status', 'confirmed')
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->pluck('room_id')
            ->toArray();
    
        // Get all rooms that are NOT in the booked list
        return $this->rooms()
            ->whereNotIn('id', $bookedRoomIds)
            ->get()
            ->groupBy('type');
    }

    public function promotion()
    {
        return $this->hasOne(HotelPromotion::class);
    }
}
