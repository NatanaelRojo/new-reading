<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateBookReadingProgressRequest",
 * title="Update Book Reading Progress Request",
 * description="Request body for updating a user's reading progress on a specific book.",
 * required={"pages_read"},
 * @OA\Property(
 * property="pages_read",
 * type="integer",
 * format="int32",
 * description="The current page number the user has read up to for this book.",
 * minimum=1,
 * example=150
 * )
 * )
 */
class UpdateBookReadingProgressRequest
{
}
