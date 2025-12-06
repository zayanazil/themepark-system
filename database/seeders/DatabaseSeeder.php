<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\ThemeParkEvent;
use App\Models\FerryTrip;
use App\Models\Ad;
use App\Models\MapLocation;
use App\models\HotelPromotion;
use App\models\EventPromotion;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Create Admin & Staff Users
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@hotel.com', 'role' => 'admin'],
            ['name' => 'Hotel Manager', 'email' => 'manager@hotel.com', 'role' => 'hotel_manager'],
            ['name' => 'Ferry Staff', 'email' => 'ferry@hotel.com', 'role' => 'ferry_staff'],
            ['name' => 'Theme Park Staff', 'email' => 'park@hotel.com', 'role' => 'theme_park_staff'],
            ['name' => 'Regular User', 'email' => 'user@email.com', 'role' => 'visitor'],
        ];

        foreach ($users as $u) {
            User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => Hash::make('1234'),
                'role' => $u['role']
            ]);
        }

        // 1. Create Hotels
        $ocean = Hotel::create(['name' => 'Ocean View Resort']);
        $jungle = Hotel::create(['name' => 'Jungle Stay']);
        $city = Hotel::create(['name' => 'City Center Hotel']);

        // 2. Rooms
        $roomTypes = [
            'Single' => ['price' => 100, 'capacity' => 1],
            'Couple' => ['price' => 180, 'capacity' => 2],
            'Family' => ['price' => 250, 'capacity' => 4],
            'Deluxe' => ['price' => 300, 'capacity' => 3],
        ];

        foreach ([$ocean, $jungle, $city] as $hotel) {
            foreach ($roomTypes as $type => $data) {
                // Create 3 rooms of each type for each hotel
                for ($i = 1; $i <= 3; $i++) {
                    Room::create([
                        'hotel_id' => $hotel->id,
                        'type' => $type,
                        'price' => $data['price'],
                        'capacity' => $data['capacity'],
                    ]);
                }
            }
        }

        // 3. Theme Park Events
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

        ThemeParkEvent::create([
            'name' => 'Volleyball',
            'description' => 'Fun beach volleyball games for all ages',
            'capacity' => 30,
            'event_date' => '2025-12-21',
            'event_time' => '11:00:00',
            'price' => 15,
            'category' => 'Beach Event', // specify the category
        ]);

        ThemeParkEvent::create([
            'name' => 'Beach Fishing Challenge',
            'description' => 'Compete to catch the biggest fish along the shore!',
            'capacity' => 20,
            'event_date' => '2025-12-22',
            'event_time' => '09:00:00',
            'price' => 20,
            'category' => 'Beach Event',
        ]);

        // 4. Ferry Trips
        FerryTrip::create([
            'route_name' => 'Harbor → Island',
            'departure_time' => now()->addDays(1)->setTime(10, 0),
            'capacity' => 15
        ]);

        FerryTrip::create([
            'route_name' => 'Island → Harbor',
            'departure_time' => now()->addDays(1)->setTime(16, 0),
            'capacity' => 15
        ]);

        FerryTrip::create([
            'route_name' => 'Harbor → Lagoon',
            'departure_time' => now()->addDays(2)->setTime(9, 30),
            'capacity' => 12
        ]);

        FerryTrip::create([
            'route_name' => 'Lagoon → Harbor',
            'departure_time' => now()->addDays(2)->setTime(15, 30),
            'capacity' => 12
        ]);

        // 5. Create Ads
        Ad::create([
            'title' => 'Dolphin Show – Don’t Miss Out!',
            'content' => 'Join us for an unforgettable Dolphin Show featuring incredible tricks and close-up experiences. Limited seats available!',
            'image_url' => 'https://images.pexels.com/photos/225869/pexels-photo-225869.jpeg'
        ]);

        // 6. Map Locations
        $locations = [
            ['name' => 'Beach', 'latitude' => 4.174315, 'longitude' => 73.518072],
            ['name' => 'Dolphin Show', 'latitude' => 4.179950, 'longitude' => 73.508560],
            ['name' => 'Fireworks Show', 'latitude' => 4.170329, 'longitude' => 73.506383],
            ['name' => 'Ocean View Resort', 'latitude' => 4.175219, 'longitude' => 73.501647],
            ['name' => 'City Center Hotel', 'latitude' => 4.175556, 'longitude' => 73.510069],
            ['name' => 'Jungle Stay', 'latitude' => 4.174061, 'longitude' => 73.513014],
        ];

        foreach ($locations as $loc) {
            MapLocation::create([
                'name' => $loc['name'],
                'latitude' => $loc['latitude'],
                'longitude' => $loc['longitude'],
            ]);
        }

        // 7. Promo
        HotelPromotion::create([
            'hotel_id' => $ocean->id,
            'title' => 'Seashell Savings',
            'discount_percent' => 5,
            'description' => 'Enjoy a special discount on your Ocean View stay!'
        ]);

        $event1 = ThemeParkEvent::where('name', 'Dolphin Show')->first();
        EventPromotion::create([
            'event_id' => $event1->id,
            'title' => 'Holiday Special',
            'discount_percent' => 20,
            'description' => 'Get 20% off for a limited time!'
        ]);

        $event2 = ThemeParkEvent::where('name', 'Volleyball')->first();
        EventPromotion::create([
            'event_id' => $event2->id,
            'title' => 'Beach Fun Offer',
            'discount_percent' => 15,
            'description' => 'Enjoy 15% off your volleyball entry!'
        ]);
    }
}
