<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [    
                    "club_id"       =>  $this->club_id,
                    "player_id"     =>  $this->player_id,
                    "date"          =>  $this->date,
                    "start_time"    =>  $this->start_time, 
                    "end_time"      =>  $this->end_time, 
                    "players_count" =>  $this->players_count,
                    "status"        =>  $this->status,
                    'created_at'    =>  $this->created_at?->toIso8601String(),
                    'updated_at'    =>  $this->updated_at?->toIso8601String(),

                    //EAGERS 

                    "player"        =>  new PlayerResource( $this->whenLoaded('player') ),
                    "club"          =>  new ClubResource(   $this->whenLoaded('club')   )

                    
        ];
    }
}
