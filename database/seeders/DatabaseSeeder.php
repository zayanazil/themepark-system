<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\ThemeParkEvent;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Hotels
        Hotel::create(['name' => 'Ocean View Resort', 'room_count' => 50]);
        Hotel::create(['name' => 'Jungle Stay', 'room_count' => 20]);
        Hotel::create(['name' => 'City Center Hotel', 'room_count' => 100]);

        // 2. Create Events
        ThemeParkEvent::create([
            'name' => 'Dolphin Show',
            'description' => 'Amazing acrobatics with dolphins',
            'capacity' => 50,
            'event_date' => '2024-12-01',
            'event_time' => '14:00:00',
            'price' => 25
        ]);

        ThemeParkEvent::create([
            'name' => 'Night Fireworks',
            'description' => 'Spectacular light show',
            'capacity' => 200,
            'event_date' => '2024-12-01',
            'event_time' => '20:00:00',
            'price' => 10
        ]);
    }
}
