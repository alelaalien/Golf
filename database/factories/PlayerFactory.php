<?php

namespace Database\Factories;

use App\Enums\PlayerStatus;
use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'club_id'   => Club::factory(), // Crea un club automáticamente si no existe, o usa uno al azar
            'name'      => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email'     => fake()->unique()->safeEmail(),
            'phone'     => fake()->phoneNumber(),
            'handicap'  => fake()->randomFloat(1, -5.0, 36.0), // Hándicap típico de golf
            'status'    => fake()->randomElement(PlayerStatus::cases())
        ];
    }
}
