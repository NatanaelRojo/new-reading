<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="UpdatePostRequest",
 * title="Update Post Request",
 * description="Request body for updating an existing post. All fields are optional.
 * Either 'book_id' can be provided in the request body, or the post update
 * can occur in the context of a book route (e.g., /api/v1/books/{book_slug}/posts/{post_slug}).",
 * @OA\Property(
 * property="body",
 * type="string",
 * description="The updated content of the post.",
 * minLength=5,
 * maxLength=1000,
 * nullable=true,
 * example="Just finished chapter 7, the plot twist was unexpected!"
 * ),
 * @OA\Property(
 * property="progress",
 * type="integer",
 * description="The updated reading progress associated with the post (e.g., page number, percentage).",
 * minimum=1,
 * nullable=true,
 * example=180
 * ),
 * @OA\Property(
 * property="book_id",
 * type="integer",
 * format="int64",
 * description="The ID of the book this post is related to.
 * This field is optional. If provided, it must be an existing book ID.
 * If the update is via a book-specific endpoint (e.g., /books/{book_slug}/posts/{post_slug}),
 * the book ID will be inferred from the route.",
 * nullable=true,
 * example=101
 * )
 * )
 */
class UpdatePostRequest
{
}
