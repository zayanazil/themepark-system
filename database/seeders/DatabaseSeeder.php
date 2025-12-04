<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\ThemeParkEvent;
use App\Models\FerryTrip;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // 1. Create Hotels
        $ocean = Hotel::create(['name' => 'Ocean View Resort']);
        $jungle = Hotel::create(['name' => 'Jungle Stay']);
        $city = Hotel::create(['name' => 'City Center Hotel']);

        // 2. Create Rooms for each hotel with room numbers
        $roomTypes = [
            'Single' => 100,
            'Couple' => 180,
            'Family' => 250,
            'Deluxe' => 300
        ];

        foreach ([$ocean, $jungle, $city] as $hotel) {
            $roomNumber = 1; // reset per hotel
            foreach ($roomTypes as $type => $price) {
                Room::create([
                    'hotel_id' => $hotel->id,
                    'type' => $type,       // exact match to ENUM
                    'price' => $price,
                    'room_number' => $roomNumber++
                ]);
            }
        }

        // 3. Create Theme Park Events
        ThemeParkEvent::create([
            'name' => 'Dolphin Show',
            'description' => 'Amazing acrobatics with dolphins',
            'capacity' => 50,
            'event_date' => '2025-12-20',
            'event_time' => '14:00:00',
            'price' => 25
        ]);

        ThemeParkEvent::create([
            'name' => 'Night Fireworks',
            'description' => 'Spectacular light show',
            'capacity' => 200,
            'event_date' => '2025-12-20',
            'event_time' => '20:00:00',
            'price' => 10
        ]);

        // 4. Create Ferry Trips
        FerryTrip::create([
            'route_name' => 'Harbor → Island',
            'departure_time' => now()->addDays(1)->setTime(10, 0),
            'capacity' => 50
        ]);

        FerryTrip::create([
            'route_name' => 'Island → Harbor',
            'departure_time' => now()->addDays(1)->setTime(16, 0),
            'capacity' => 50
        ]);

        FerryTrip::create([
            'route_name' => 'Harbor → Lagoon',
            'departure_time' => now()->addDays(2)->setTime(9, 30),
            'capacity' => 30
        ]);

        FerryTrip::create([
            'route_name' => 'Lagoon → Harbor',
            'departure_time' => now()->addDays(2)->setTime(15, 30),
            'capacity' => 30
        ]);
    }
}
