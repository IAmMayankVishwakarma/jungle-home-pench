<?php

namespace Database\Factories;

use App\Models\Room\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RoomType>
 */
class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'code' => Str::upper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 8)),
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'target_room_count' => 10,
            'base_price' => fake()->randomFloat(2, 1000, 10000),
            'currency' => 'INR',
            'room_size' => fake()->randomFloat(2, 200, 1000),
            'room_size_unit' => 'sq_ft',
            'bed_type' => fake()->randomElement(['King', 'Queen', 'Twin']),
            'number_of_beds' => 1,
            'number_of_adults' => 2,
            'number_of_children' => 1,
            'maximum_occupancy' => 3,
            'extra_person_allowed' => true,
            'maximum_extra_persons' => 1,
            'extra_person_charge' => 500,
            'extra_bed_available' => true,
            'extra_bed_charge' => 750,
            'pet_friendly' => false,
            'room_view' => 'Forest',
            'floor_or_building' => 'Main',
            'is_active' => true,
        ];
    }
}
