<?php

namespace Database\Factories;

use App\Models\Room\Room;
use App\Models\Room\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roomType = RoomType::factory()->create();
        $serial = fake()->unique()->numberBetween(1, 100000);

        return [
            'room_type_id' => $roomType->getKey(),
            'room_code' => $roomType->code.'-'.str_pad((string) $serial, 3, '0', STR_PAD_LEFT),
            'serial_number' => $serial,
            'operational_status' => 'active',
            'housekeeping_status' => 'clean',
            'floor_or_building' => $roomType->floor_or_building,
        ];
    }
}
