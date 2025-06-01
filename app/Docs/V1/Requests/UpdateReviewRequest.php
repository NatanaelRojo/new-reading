<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdateReviewRequest",
 * title="Update Review Request",
 * description="Request body for updating an existing book review. All fields are optional.",
 * @OA\Property(
 * property="rating",
 * type="integer",
 * description="The updated rating for the book, from 1 to 5.",
 * minimum=1,
 * maximum=5,
 * nullable=true,
 * example=4
 * ),
 * @OA\Property(
 * property="comment",
 * type="string",
 * description="The updated text content of the review.",
 * nullable=true,
 * example="Still a great book, but the ending felt a little rushed."
 * ),
 * @OA\Property(
 * property="book_id",
 * type="integer",
 * format="int64",
 * description="The ID of the book this review is for. This field is optional and typically not changed during an update. If provided, it must be an existing book ID.",
 * nullable=true,
 * example=101
 * )
 * )
 */
class UpdateReviewRequest
{
}
