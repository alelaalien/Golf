<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    
    public function run(): void
    {
         $clubs = Club::all();

         if($clubs->isEmpty())
            {
                Player::factory()->count(25)->create();

                return;
            }
        
        Club::all()->each(
            function ($club) {
                Player::factory()->count(2)->create([
                    "club_id" =>$club->id
                ]);
            }
        );
    }
}
