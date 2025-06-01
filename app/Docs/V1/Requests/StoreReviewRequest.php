<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="StoreReviewRequest",
 * title="Store Review Request",
 * description="Request body for creating a new book review.
 * Either 'book_id' must be provided in the request body, or the review must be created
 * in the context of a book route (e.g., /api/v1/books/{book_slug}/reviews).",
 * required={"rating", "comment"},
 * @OA\Property(
 * property="rating",
 * type="integer",
 * description="The rating given to the book, from 1 to 5.",
 * minimum=1,
 * maximum=5,
 * example=5
 * ),
 * @OA\Property(
 * property="comment",
 * type="string",
 * description="The text content of the review.",
 * example="This book was a truly immersive experience, I couldn't put it down!"
 * ),
 * @OA\Property(
 * property="book_id",
 * type="integer",
 * format="int64",
 * description="The ID of the book this review is for.
 * This field is required if the review is not created via a book-specific endpoint (e.g., /reviews).
 * It is optional if the review is created via a book-specific endpoint (e.g., /books/{book_slug}/reviews),
 * as the book ID will be inferred from the route.",
 * nullable=true,
 * example=101
 * )
 * )
 */
class StoreReviewRequest
{
}
