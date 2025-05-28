<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateBookTagRequest",
 * title="Update Book Tag Request",
 * description="Request body for updating a user's tag association for a specific book.",
 * required={"tag_id"},
 * @OA\Property(
 * property="tag_id",
 * type="integer",
 * format="int64",
 * description="The ID of the tag to associate with the book for the current user. Must be an existing tag ID.",
 * example=1
 * )
 * )
 */
class UpdateBookTagRequest
{
}
