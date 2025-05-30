<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="StoreTagRequest",
 * title="Store Tag Request",
 * description="Request body for creating a new tag.",
 * required={"name"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="The name of the new tag.",
 * maxLength=255,
 * example="Fiction"
 * )
 * )
 */
class StoreTagRequest
{
}
