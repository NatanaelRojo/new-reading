<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateCommentRequest",
 * title="Update Comment Request",
 * description="Request body for updating an existing comment. All fields are optional.",
 * @OA\Property(
 * property="body",
 * type="string",
 * description="The updated content of the comment.",
 * minLength=5,
 * maxLength=100,
 * nullable=true,
 * example="This is my updated and improved comment!"
 * )
 * )
 */
class UpdateCommentRequest
{
}
