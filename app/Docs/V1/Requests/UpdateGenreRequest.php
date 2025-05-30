<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateGenreRequest",
 * title="Update Genre Request",
 * description="Request body for updating an existing genre.",
 * @OA\Property(
 * property="name",
 * type="string",
 * description="The new name for the genre.",
 * maxLength=255,
 * nullable=true,
 * example="High Fantasy"
 * )
 * )
 */
class UpdateGenreRequest
{
}
