<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClubRequest;
use App\Http\Requests\ClubSlugRequest;
use App\Http\Resources\ClubResource; 
use App\Models\Club;
use App\Services\ClubService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected ClubService $service)
    {
        
    }
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer("perPage", 25), 100);
        $filters= $request->only("name", "status", "slug");

        $clubs = $this->service->getAll($filters, $perPage);

        return   ClubResource::Collection ($clubs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClubRequest $request) : JsonResponse
    {
         $data = $request->validated();

         $club = $this->service->save($data);

         return (new ClubResource($club))->response()->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Club $club): ClubResource
    {
        return new ClubResource($club);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClubRequest $request, Club $club): ClubResource
    {
        $data = $request->validated();

        $response = $this->service->update($data, $club);

        return new ClubResource($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Club $club): JsonResponse
    {
        $this->service->destroy($club);

        return response()->json(null, 204);
    }

    public function updateSlug(ClubSlugRequest $request, Club $club): ClubResource
    {
        $data = $request->validated("slug");
        $updated = $this->service->updateSlug($data, $club);

        return new ClubResource($updated); 
    }
}
