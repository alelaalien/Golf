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
use OpenApi\Attributes as OA;

class PlayerController extends Controller
{
    public function __construct(protected PlayerService $service)
    {
    }

    #[OA\Get(
        path: "/players",
        operationId: "getPlayersList",
        summary: "Obtener listado de jugadores",
        description: "Retorna una lista paginada de jugadores con filtros opcionales por nombre, apellido y club_id.",
        tags: ["Players"],
        parameters: [
            new OA\Parameter(name: "perPage", description: "Cantidad de elementos por página (máximo 100)", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 25)),
            new OA\Parameter(name: "name", description: "Filtrar por nombre del jugador", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "last_name", description: "Filtrar por apellido del jugador", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "club_id", description: "Filtrar por ID del club asociado", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer("perPage", 25), 100);
        $filters = $request->only(["name", "last_name", "club_id"]);
        $data = $this->service->getAll($filters, $perPage);

        return PlayerResource::collection($data);
    }

    #[OA\Post(
        path: "/players",
        operationId: "storePlayer",
        summary: "Crear un nuevo jugador",
        description: "Registra un nuevo jugador en el sistema asociado a un club.",
        tags: ["Players"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["club_id", "name", "last_name", "email"],
                properties: [
                    new OA\Property(property: "club_id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Alejandra"),
                    new OA\Property(property: "last_name", type: "string", example: "Zepeda"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "alejandra@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "+34600123456"),
                    new OA\Property(property: "handicap", type: "number", format: "float", example: 12.5),
                    new OA\Property(property: "status", type: "string", example: "active"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Jugador creado exitosamente"),
            new OA\Response(response: 422, description: "Error de validación"),
        ]
    )]
    public function store(PlayerRequest $request): JsonResponse
    {
        $data = $request->validated();

        $player = $this->service->save($data);

        return (new PlayerResource($player))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: "/players/{player}",
        operationId: "getPlayerById",
        summary: "Mostrar un jugador específico",
        description: "Retorna la información detallada de un jugador mediante su ID.",
        tags: ["Players"],
        parameters: [
            new OA\Parameter(name: "player", description: "ID del jugador", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
            new OA\Response(response: 404, description: "Jugador no encontrado"),
        ]
    )]
    public function show(Player $player): PlayerResource
    {
        return new PlayerResource($player);
    }

    #[OA\Put(
        path: "/players/{player}",
        operationId: "updatePlayer",
        summary: "Actualizar un jugador existente",
        description: "Modifica los datos de un jugador existente.",
        tags: ["Players"],
        parameters: [
            new OA\Parameter(name: "player", description: "ID del jugador", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "club_id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Alejandra Actualizada"),
                    new OA\Property(property: "last_name", type: "string", example: "Zepeda"),
                    new OA\Property(property: "phone", type: "string", example: "+346667788"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "alejandra.nueva@example.com"),
                    new OA\Property(property: "handicap", type: "number", format: "float", example: 10.0),
                    new OA\Property(property: "status", type: "string", example: "active"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Jugador actualizado con éxito"),
            new OA\Response(response: 404, description: "Jugador no encontrado"),
            new OA\Response(response: 422, description: "Error de validación"),
        ]
    )]
    public function update(PlayerRequest $request, Player $player): PlayerResource
    {
        $data = $request->validated();
        $updatedPlayer = $this->service->update($player, $data);

        return new PlayerResource($updatedPlayer);
    }

    #[OA\Delete(
        path: "/players/{player}",
        operationId: "deletePlayer",
        summary: "Eliminar un jugador",
        description: "Elimina un jugador del sistema.",
        tags: ["Players"],
        parameters: [
            new OA\Parameter(name: "player", description: "ID del jugador", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 204, description: "Jugador eliminado exitosamente (sin contenido)"),
            new OA\Response(response: 404, description: "Jugador no encontrado"),
        ]
    )]
    public function destroy(Player $player): JsonResponse
    {
        $this->service->destroy($player);

        return response()->json(null, 204);
    }
}