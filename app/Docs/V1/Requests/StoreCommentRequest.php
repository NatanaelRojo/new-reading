<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="StoreCommentRequest",
 * title="Store Comment Request",
 * description="Request body for creating a new comment on a book or review.",
 * required={"body"},
 * @OA\Property(
 * property="body",
 * type="string",
 * description="The content of the comment.",
 * minLength=5,
 * maxLength=100,
 * example="This is my insightful comment about the book!"
 * )
 * )
 */
class StoreCommentRequest
{
}
