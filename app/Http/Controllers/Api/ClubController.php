<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClubResource;
use App\Services\ClubService;
 use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected ClubService $service)
    {
        
    }
    public function index(): AnonymousResourceCollection
    {
        $clubs = $this->service->getAll();

        return   ClubResource::Collection ($clubs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
