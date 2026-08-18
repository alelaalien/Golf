<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(protected PlayerService $service)
    {
        
    }


    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer("perPage", 25), 100);
        $filters = $request->only(["name", "last_name", "club_id"]);
        $data = $this->service->getAll($filters, $perPage);

        return  PlayerResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $player = $this->service->save($data);

        return (new PlayerResource($player))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player) : PlayerResource
    {
        return new PlayerResource($player);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, Player $player)
    {
        $data = $request->validated();
        $updatedPlayer = $this->service->update($player, $data);

        return new PlayerResource($updatedPlayer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player): JsonResponse
    {
        $this->service->destroy($player);

        return response()->json(null, 204);
    }
}
