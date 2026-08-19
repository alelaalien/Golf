<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Golf Court Reservation API",
    description: "Documentación de la API para el sistema de reservas de canchas de golf."
)]
#[OA\Server(
    url: "http://127.0.0.1/api",
    description: "API Server"
)]
class ApiDocumentation
{
}