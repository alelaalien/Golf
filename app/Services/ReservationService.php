<?php

namespace App\Services;

use App\Exceptions\ReservationConflictException;
use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReservationService
{

    public function save(array $data) : Reservation
    {
        return DB::transaction(function () use ($data) 
        {
            if($this->checkScheduleOverlap($data))
                {
                    throw new ReservationConflictException();
                }
            
            return   Reservation::create($data);
        });
          
    }

    public function update( array $data, Reservation $reservation) : Reservation
    {
        return DB::transaction(function () use($reservation, $data)
        {
                if($this->checkScheduleOverlap($data, $reservation->id))
                {   
                     throw new ReservationConflictException();
                }
       
               $reservation->update($data);

        return $reservation; 
        });
    }

    public function delete(Reservation $reservation) : bool
    {

        return $reservation->delete();
    } 

    public function getAll(array $filters = [], int $perPage = 25 ): LengthAwarePaginator
    {
        return Reservation::with(["club", "player"])
        
        //club
                ->when(
                    $filters["club_id"] ?? null,
                    function ($query, $whenParameter) {
                        $query->where("club_id", $whenParameter);
                    }
                )
        //player
                ->when(
                    $filters["player_id"] ?? null,
                    function ($query, $whenParameter) {
                        $query->where("player_id", $whenParameter);
                    }
                )
        //reservation
                ->when(
                    $filters["status"] ?? null,
                    function ($query, $whenParameter) {
                        $query->where("status", $whenParameter);
                    }
                )
                ->when(
                    $filters["date"] ?? null,
                    function ($query, $whenParameter) {
                        $query->where("date", $whenParameter);
                    }
                )
                ->latest()
                ->paginate($perPage);
    }

    public function getById(int $id) : Reservation
    {
        return Reservation::with("club", "player")
                            ->findOrFail($id);
    }


    ////// validations

    public function checkScheduleOverlap(array $data, ?int $excludeId = null) : bool
    {
         return  Reservation::where('club_id', $data['club_id']) 
            ->where('date', $data['date'])
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->when($excludeId, 
                    function($query, $excludeId)
                    {
                        $query->where("id", "!=", $excludeId);
                    })
            ->lockForUpdate()
            ->exists();

    }

        
}