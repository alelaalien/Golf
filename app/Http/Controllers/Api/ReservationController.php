<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        protected ReservationService $service
    )
    {
        
    }

    public function index( Request $request):AnonymousResourceCollection
    {
        $perPage = min($request->integer('perPage', 25), 100);

        $filters = $request->only(["status", "date", "club_id", "player_id"]);
        
        $all = $this->service->getAll($filters, $perPage);

        return ReservationResource::collection($all);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $reservation = $this->service->save($validated);

        return ( new ReservationResource($reservation))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): ReservationResource
    {
         
        return  new ReservationResource($reservation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationRequest $request, Reservation $reservation): ReservationResource
    {

        $validated = $request->validated();

        $updated = $this->service->update($validated, $reservation);

        return new ReservationResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
       $this->service->delete($reservation);

       return response()->json(null, 204);
    }
}
