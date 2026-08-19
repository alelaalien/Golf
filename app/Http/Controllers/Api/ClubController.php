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
use OpenApi\Attributes as OA;

class ClubController extends Controller
{
    public function __construct(protected ClubService $service)
    {
    }

    #[OA\Get(
        path: "/clubs",
        operationId: "getClubsList",
        summary: "Obtener listado de clubs",
        description: "Retorna una lista paginada de clubs con filtros opcionales por nombre, estado y slug.",
        tags: ["Clubs"],
        parameters: [
            new OA\Parameter(name: "perPage", description: "Cantidad de elementos por página (máximo 100)", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 25)),
            new OA\Parameter(name: "name", description: "Filtrar por nombre del club", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "status", description: "Filtrar por estado del club", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "slug", description: "Filtrar por slug", in: "query", required: false, schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer("perPage", 25), 100);
        $filters = $request->only("name", "status", "slug");

        $clubs = $this->service->getAll($filters, $perPage);

        return ClubResource::Collection($clubs);
    }

    #[OA\Post(
        path: "/clubs",
        operationId: "storeClub",
        summary: "Crear un nuevo club",
        description: "Registra un nuevo club de golf en el sistema.",
        tags: ["Clubs"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Club de Golf Costa del Sol"),
                    new OA\Property(property: "slug", type: "string", example: "club-de-golf-costa-del-sol"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "contacto@golfcostasol.com"),
                    new OA\Property(property: "phone", type: "string", example: "+34952123456"),
                    new OA\Property(property: "status", type: "string", example: "active"),
                    new OA\Property(property: "configuration", type: "object", example: ["courts_count" => 9]),
                    new OA\Property(property: "address", type: "string", example: "Carretera de Cádiz Km 204"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Club creado exitosamente"),
            new OA\Response(response: 422, description: "Error de validación"),
        ]
    )]
    public function store(ClubRequest $request): JsonResponse
    {
        $data = $request->validated();

        $club = $this->service->save($data);

        return (new ClubResource($club))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: "/clubs/{club}",
        operationId: "getClubById",
        summary: "Mostrar un club específico",
        description: "Retorna la información detallada de un club mediante su ID.",
        tags: ["Clubs"],
        parameters: [
            new OA\Parameter(name: "club", description: "ID del club", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Operación exitosa"),
            new OA\Response(response: 404, description: "Club no encontrado"),
        ]
    )]
    public function show(Club $club): ClubResource
    {
        return new ClubResource($club);
    }

    #[OA\Put(
        path: "/clubs/{club}",
        operationId: "updateClub",
        summary: "Actualizar un club existente",
        description: "Modifica los datos de un club existente. El campo 'name' es obligatorio.",
        tags: ["Clubs"],
        parameters: [
            new OA\Parameter(name: "club", description: "ID del club", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Club Actualizado"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "nuevo@golfcostasol.com"),
                    new OA\Property(property: "phone", type: "string", example: "+34952999888"),
                    new OA\Property(property: "status", type: "string", example: "active"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Club actualizado con éxito"),
            new OA\Response(response: 404, description: "Club no encontrado"),
            new OA\Response(response: 422, description: "Error de validación"),
        ]
    )]
    public function update(ClubRequest $request, Club $club): ClubResource
    {
        $data = $request->validated();

        $response = $this->service->update($data, $club);

        return new ClubResource($response);
    }

    #[OA\Delete(
        path: "/clubs/{club}",
        operationId: "deleteClub",
        summary: "Eliminar un club",
        description: "Elimina un club del sistema de forma lógica.",
        tags: ["Clubs"],
        parameters: [
            new OA\Parameter(name: "club", description: "ID del club", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 204, description: "Club eliminado exitosamente (sin contenido)"),
            new OA\Response(response: 404, description: "Club no encontrado"),
        ]
    )]
    public function destroy(Club $club): JsonResponse
    {
        $this->service->destroy($club);

        return response()->json(null, 204);
    }

    #[OA\Patch(
        path: "/clubs/{club}/slug",
        operationId: "updateClubSlug",
        summary: "Actualizar el slug de un club",
        description: "Modifica específicamente el slug de un club mediante una petición dedicada.",
        tags: ["Clubs"],
        parameters: [
            new OA\Parameter(name: "club", description: "ID del club", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["slug"],
                properties: [
                    new OA\Property(property: "slug", type: "string", example: "nuevo-slug-personalizado"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Slug actualizado con éxito"),
            new OA\Response(response: 422, description: "Error de validación o slug duplicado"),
        ]
    )]
    public function updateSlug(ClubSlugRequest $request, Club $club): ClubResource
    {
        $data = $request->validated("slug");
        $updated = $this->service->updateSlug($data, $club);

        return new ClubResource($updated);
    }
}