<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
        
        [
            "id"        =>  $this->id,
            "club_id"   =>  $this->club_id,        
            "full_name" => $this->name . " " . $this->last_name,
            "email"     => $this->email,
            "phone"     => $this->phone,
            "handicap"  => $this->handicap,
            "status"    => $this->status,
            'created_at'=> $this->created_at?->toIso8601String(),
            'updated_at'=> $this->updated_at?->toIso8601String(),

            //EAGER

            "club"      => new ClubResource(    $this->whenLoaded('club')   )

        ];
    }
}
