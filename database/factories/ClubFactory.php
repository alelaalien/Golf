<?php

namespace Database\Factories;

use App\Enums\ClubStatus;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Club>
 */
class ClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Club::class;

    public function definition(): array
    {

        $name = fake()->company();

        return [
            "name"      => $name,
            "slug"      =>Str::slug($name),
            "email"     =>fake()->unique()->safeEmail(),
            "phone"     =>fake()->phoneNumber(),
            "status"    =>fake()->randomElement(ClubStatus::cases()),
            'configuration' => [
                'courts_count'  => fake()->numberBetween(1, 8),
                'opening_hours' => '08:00-23:00',
                'allows_guests' => fake()->boolean(),
            ],
            'address'       => fake()->address(),
        ];
    }
}
