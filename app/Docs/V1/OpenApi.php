<?php

namespace App\Docs\V1; // Or your controller's namespace

const MAX_PUBLICATION_YEAR = 2025;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="New Reading Api",
 * description="API documentation for your Laravel application.",
 * @OA\Contact(
 * email="rojonatanael99@gmail.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Demo API Server"
 * )
 *
 * @OA\SecurityScheme(
 * securityScheme="bearerAuth",
 * in="header",
 * name="bearerAuth",
 * type="http",
 * scheme="bearer",
 * bearerFormat="JWT",
 * )
 */
class OpenApi
{
    // This class serves purely as a container for OpenAPI annotations.
    // It doesn't need any actual methods or properties.
}
