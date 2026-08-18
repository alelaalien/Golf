<?php

namespace App\Services;

use App\Models\Club;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ClubService
{
    public function save(array $data): Club
    {
        $data['slug'] ??= Str::slug($data['name']);
        return Club::create($data);
    }

    public function update(array $data, Club $club): Club
    {
        if(isset($data["slug"])) unset($data["slug"]);

        $club->update($data);
        
        return $club;
    }
        public function updateSlug(string $slug, Club $club): Club
    {
         
               $club->slug = $slug;

               if(!$club->isDirty("slug")) return $club;
               
               $club->save();
            
        
        return $club;
    }

    public function destroy(Club $club): bool
    {
        return $club->delete();
    }

    public function getAll(array $filters = [], int $per_page = 25) : LengthAwarePaginator
    {
        return Club::with(["reservations", "players"])
                ->when($filters["name"] ?? null,
                    function ($query, $whenParameter) 
                    {
                        $query->where("name", "like", "%{$whenParameter}%");
                    }) 
                ->when($filters["slug"]?? null,
                    function ($query, $whenParameter) 
                    {
                        $query->where("slug", $whenParameter);
                    })
                ->when($filters["status"]?? null, 
                    function ($query, $whenParameter) 
                    {
                        $query->where("status", $whenParameter);
                    })
                ->latest()
                ->paginate($per_page);

    }              
}