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
use OpenApi\Attributes as OA;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $service)
    {
    }

    #[OA\Get(
        path: "/reservations",
        operationId: "getReservationsList",
        summary: "Obtener listado de reservas",
        description: "Retorna una lista paginada de reservas con filtros opcionales por estado, fecha, club_id y player_id.",
        tags: ["Reservations"],
        parameters: [
            new OA\Parameter(name: "perPage", description: "Cantidad de elementos por página (máximo 100)", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 25)),
            new OA\Parameter(name: "status", description: "Filtrar por estado de la reserva", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "date", description: "Filtrar por fecha (YYYY-MM-DD)", in: "query", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "club_id", description: "Filtrar por ID del club", in: "query", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "player_id", description: "Filtrar por ID del jugador", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('perPage', 25), 100);

        $filters = $request->only(["status", "date", "club_id", "player_id"]);

        $all = $this->service->getAll($filters, $perPage);

        return ReservationResource::collection($all);
    }

    #[OA\Post(
        path: "/reservations",
        operationId: "storeReservation",
        summary: "Crear una nueva reserva",
        description: "Registra una nueva reserva de cancha de golf.",
        tags: ["Reservations"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["club_id", "player_id", "date", "start_time", "end_time", "players_count", "status"],
                properties: [
                    new OA\Property(property: "club_id", type: "integer", example: 1),
                    new OA\Property(property: "player_id", type: "integer", example: 1),
                    new OA\Property(property: "date", type: "string", format: "date", example: "2026-08-26"),
                    new OA\Property(property: "start_time", type: "string", example: "11:00"),
                    new OA\Property(property: "end_time", type: "string", example: "13:00"),
                    new OA\Property(property: "players_count", type: "integer", example: 2),
                    new OA\Property(property: "status", type: "string", example: "pending"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Reserva creada exitosamente"),
            new OA\Response(response: 422, description: "Error de validación (campos faltantes o con formato incorrecto)"),
            new OA\Response(response: 409, description: "Conflicto de horario: ya existe una reserva que se solapa"),
        ]
    )]
    public function store(ReservationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $reservation = $this->service->save($validated);

        return (new ReservationResource($reservation))
                ->response()
                ->setStatusCode(201);
    }

    #[OA\Get(
        path: "/reservations/{reservation}",
        operationId: "getReservationById",
        summary: "Mostrar una reserva específica",
        description: "Retorna la información detallada de una reserva mediante su ID.",
        tags: ["Reservations"],
        parameters: [
            new OA\Parameter(name: "reservation", description: "ID de la reserva", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
            new OA\Response(response: 404, description: "Reserva no encontrada"),
        ]
    )]
    public function show(Reservation $reservation): ReservationResource
    {
        return new ReservationResource($reservation);
    }

    #[OA\Put(
        path: "/reservations/{reservation}",
        operationId: "updateReservation",
        summary: "Actualizar una reserva existente",
        description: "Modifica los datos de una reserva existente de forma parcial o total.",
        tags: ["Reservations"],
        parameters: [
            new OA\Parameter(name: "reservation", description: "ID de la reserva", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "club_id", type: "integer", example: 1),
                    new OA\Property(property: "player_id", type: "integer", example: 1),
                    new OA\Property(property: "date", type: "string", format: "date", example: "2026-08-26"),
                    new OA\Property(property: "start_time", type: "string", example: "11:00"),
                    new OA\Property(property: "end_time", type: "string", example: "13:00"),
                    new OA\Property(property: "players_count", type: "integer", example: 2),
                    new OA\Property(property: "status", type: "string", example: "confirmed"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Reserva actualizada con éxito"),
            new OA\Response(response: 404, description: "Reserva no encontrada"),
            new OA\Response(response: 422, description: "Error de validación"),
            new OA\Response(response: 409, description: "Conflicto de horario: ya existe otra reserva que se solapa"),
        ]
    )]
    public function update(ReservationRequest $request, Reservation $reservation): ReservationResource
    {
        $validated = $request->validated();

        $updated = $this->service->update($validated, $reservation);

        return new ReservationResource($updated);
    }

    #[OA\Delete(
        path: "/reservations/{reservation}",
        operationId: "deleteReservation",
        summary: "Eliminar una reserva",
        description: "Elimina una reserva del sistema.",
        tags: ["Reservations"],
        parameters: [
            new OA\Parameter(name: "reservation", description: "ID de la reserva", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 204, description: "Reserva eliminada exitosamente (sin contenido)"),
            new OA\Response(response: 404, description: "Reserva no encontrada"),
        ]
    )]
    public function destroy(Reservation $reservation): JsonResponse
    {
        $this->service->delete($reservation);

        return response()->json(null, 204);
    }
}