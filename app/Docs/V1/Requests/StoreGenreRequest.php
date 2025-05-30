<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="StoreGenreRequest",
 * title="Store Genre Request",
 * description="Request body for creating a new genre.",
 * required={"name"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="The name of the new genre.",
 * maxLength=255,
 * example="Science Fiction"
 * )
 * )
 */
class StoreGenreRequest
{
}
