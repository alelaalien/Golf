<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class Reservation extends Model
{
    use HasFactory;
    
    protected $table = "reservations";

    protected $guarded = [];

    protected $fillable = 
    [
        "player_id",
        "date",
        "start_time",
        "end_time", 
        "players_count",
        "status",
        "club_id"
    ];

    #[Override]
    protected function casts(): array
    {
        return [

            "status"        =>  ReservationStatus::class,
            "start_time"    =>  "datetime",
            "end_time"      =>  "datetime"
        ];
    }

    public function club() : BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function player() : BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

}
