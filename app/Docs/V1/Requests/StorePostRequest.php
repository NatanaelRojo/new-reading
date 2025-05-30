<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="StorePostRequest",
 * title="Store Post Request",
 * description="Request body for creating a new post.
 * Either 'book_id' must be provided in the request body, or the post must be created
 * in the context of a book route (e.g., /api/v1/books/{book_slug}/posts).",
 * required={"body", "progress"},
 * @OA\Property(
 * property="body",
 * type="string",
 * description="The content of the post.",
 * minLength=5,
 * example="This book is incredibly captivating. Highly recommend it!"
 * ),
 * @OA\Property(
 * property="progress",
 * type="integer",
 * description="The reading progress associated with the post (e.g., page number, percentage).",
 * minimum=1,
 * maximum=100,
 * example=150
 * ),
 * @OA\Property(
 * property="book_id",
 * type="integer",
 * format="int64",
 * description="The ID of the book this post is related to.
 * This field is required if the post is not created via a book-specific endpoint (e.g., /posts).
 * It is optional if the post is created via a book-specific endpoint (e.g., /books/{book_slug}/posts),
 * as the book ID will be inferred from the route.",
 * nullable=true,
 * example=101
 * )
 * )
 */
class StorePostRequest
{
}
