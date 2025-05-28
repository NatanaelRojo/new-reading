<?php

namespace App\Docs\V1\Requests;

/**
 * @OA\Schema(
 * schema="FilterBookRequest",
 * title="Filter Book Request",
 * description="Request body for filtering and paginating books. All fields are optional.",
 * @OA\Property(
 * property="title",
 * type="string",
 * description="Filter books by title (partial match).",
 * nullable=true,
 * example="galaxy"
 * ),
 * @OA\Property(
 * property="author_name",
 * type="string",
 * description="Filter books by author's full name (partial match).",
 * nullable=true,
 * example="douglas adams"
 * ),
 * @OA\Property(
 * property="genre_name",
 * type="string",
 * description="Filter books by genre name (partial match).",
 * nullable=true,
 * example="science fiction"
 * ),
 * @OA\Property(
 * property="tag_name",
 * type="string",
 * description="Filter books by tag name (partial match).",
 * nullable=true,
 * example="classics"
 * ),
 * @OA\Property(
 * property="year",
 * type="integer",
 * format="int32",
 * description="Filter books by publication year. Must be a 4-digit integer.",
 * minimum=1000,
 * maximum=2025,
 * nullable=true,
 * example=1979
 * ),
 * @OA\Property(
 * property="per_page",
 * type="integer",
 * format="int32",
 * description="Number of items to return per page.",
 * minimum=1,
 * maximum=50,
 * nullable=true,
 * example=15
 * ),
 * @OA\Property(
 * property="page",
 * type="integer",
 * format="int32",
 * description="The page number to retrieve.",
 * minimum=1,
 * nullable=true,
 * example=1
 * )
 * )
 */
class FilterBookRequest
{
}
