<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Reservation;
use Database\Factories\ReservationFactory; 
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    
    
    public function run(): void
    {
        $players = Player::all();

        if($players->isEmpty())
            {
                Reservation::factory()->count(30)->create();
                return;
            }
        $players->each( function ( $player) {
            Reservation::factory()->count(3)->create([
                "club_id"=>$player->club_id,
                "player_id"=>$player->id,
            ]);
        });

    }
}
