<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $guarded = [];

    protected $fillable = [
            "id"        ,
            "club_id"   ,       
            "name"      ,  
            "last_name" ,   
            "email"     ,
            "phone"     ,
            "handicap"  , 
            "status"     
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
