<?php

namespace App\Models;

use App\Enums\ClubStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Club extends Model
{

    protected $table = "clubs";
   
    protected $fillable = 
    [
        "name", 
        "slug",
        "email",
        "phone",
        "status",
        "configuration",
        "address"
    ];

    protected $guarded = [];

    #[Override]
    protected function casts(): array
    {
        return [

            'status'        => ClubStatus::class,
            'configuratios' => 'array'
        ];
    }

    public function players() : HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
