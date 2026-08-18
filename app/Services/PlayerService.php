<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Pagination\LengthAwarePaginator;

class PlayerService
{
    public function save(array $data):Player
    {

        return Player::create($data);
    }

    public function update(Player $player, array $data):Player
    {
        $player->update($data);

        return $player;

    }
    public function destroy(Player $player): bool
    {
        return $player->delete();

    }

    public function getAll(array $filters = [], $per_page =25) : LengthAwarePaginator 
    {
        return Player::with("club")
                ->when($filters["club_id"]?? null,
                function ($query, $whenParameter) 
                {
                        $query->where("club_id", $whenParameter);
                })
                ->when($filters["name"]?? null,
                function ($query, $whenParameter) 
                {
                    $query->where("last_name", $whenParameter);
                })
                ->latest()
                ->paginate($per_page);
    }
}