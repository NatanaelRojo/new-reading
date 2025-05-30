<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateTagRequest",
 * title="Update Tag Request",
 * description="Request body for updating an existing tag. All fields are optional.",
 * @OA\Property(
 * property="name",
 * type="string",
 * description="The new name for the tag.",
 * maxLength=255,
 * nullable=true,
 * example="Non-Fiction"
 * )
 * )
 */
class UpdateTagRequest
{
}
