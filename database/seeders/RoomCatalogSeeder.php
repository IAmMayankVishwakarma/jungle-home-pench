<?php

namespace Database\Seeders;

use App\Models\Room\Amenity;
use App\Models\Room\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoomCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            'Air Conditioning' => 'air-conditioning.svg',
            '24-Hour Room Service' => 'room-service.svg',
            'In-Room Dining' => 'in-room-dining.svg',
            'Mineral Water' => 'mineral-water.svg',
            'Attached Bathroom' => 'attached-bathroom.svg',
            'Queen Size Bed' => 'queen-size-bed.svg',
            'King Size Bed' => 'king-size-bed.svg',
            'Pillow & Blanket' => 'pillow-blanket.svg',
            'Closet' => 'closet.svg',
            'Mirror' => 'mirror.svg',
            'Reading Lamp' => 'reading-lamp.svg',
            'Television' => 'television.svg',
            'Doctor on Call' => 'doctor-on-call.svg',
            'Hand Sanitizer' => 'hand-sanitizer.svg',
            'Coffee' => 'coffee.svg',
            'Wi-Fi' => 'wifi.svg',
            'Breakfast' => 'breakfast.svg',
            'Game Center' => 'game-center.svg',
        ];

        foreach ($amenities as $name => $filename) {
            Amenity::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'icon' => $filename
                        ? 'assets/images/amenities/'.$filename
                        : null,
                    'is_active' => true,
                ],
            );
        }

        $features = [
            'Jungle View',
            'Pool View',
            'Private Sitout',
            'Completely Isolated',
        ];

        $featureIcons = [
            'Jungle View' => 'jungle-view.svg',
            'Pool View' => 'pool-view.svg',
            'Private Sitout' => 'private-sitout.svg',
            'Completely Isolated' => 'completely-isolated.svg',
        ];

        foreach ($features as $name) {
            Feature::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'icon' => 'assets/images/features/'.$featureIcons[$name],
                    'is_active' => true,
                ],
            );
        }
    }
}
