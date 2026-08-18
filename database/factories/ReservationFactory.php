<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\Club;
use App\Models\Player;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    
    protected $model = Reservation::class;

    public function definition(): array
    {
        $start_time = fake()->dateTimeBetween("now", "+60 days");
        $end_time = (clone $start_time)->modify("+2 hours");

        return [
             'club_id'      => Club::factory(),
            'player_id'     => Player::factory(),
            'date'          => $start_time->format('Y-m-d'),
            'start_time'    => $start_time,
            'end_time'      => $end_time,
            'players_count' => fake()->numberBetween(1, 4), // Partido típico de golf
            'status'        => fake()->randomElement(ReservationStatus::cases()),
        ];
    }
}
