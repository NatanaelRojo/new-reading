<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Tag;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @OA\Schema(
 * schema="UpdateBookRequest",
 * title="Update Book Request",
 * description="Request body for updating an existing Book. All fields are optional, and only provided fields will be updated.",
 * @OA\Property(
 * property="title",
 * type="string",
 * description="The updated title of the book.",
 * maxLength=255,
 * nullable=true,
 * example="The Completely Revised Hitchhiker's Guide"
 * ),
 * @OA\Property(
 * property="synopsis",
 * type="string",
 * description="An updated brief summary or outline of the book's plot.",
 * nullable=true,
 * example="Arthur Dent's continuing misadventures across the universe."
 * ),
 * @OA\Property(
 * property="isbn",
 * type="string",
 * description="The updated International Standard Book Number.",
 * maxLength=255,
 * nullable=true,
 * example="978-1-840-23652-3"
 * ),
 * @OA\Property(
 * property="published_at",
 * type="string",
 * format="date",
 * description="The updated publication date (YYYY-MM-DD).",
 * nullable=true,
 * example="1980-01-01"
 * ),
 * @OA\Property(
 * property="pages_amount",
 * type="integer",
 * format="int32",
 * description="The updated total number of pages in the book.",
 * minimum=1,
 * nullable=true,
 * example=250
 * ),
 * @OA\Property(
 * property="chapters_amount",
 * type="integer",
 * format="int32",
 * description="The updated total number of chapters in the book.",
 * minimum=1,
 * nullable=true,
 * example=40
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * format="url",
 * description="Optional URL to the book's new cover image.",
 * nullable=true,
 * example="https://example.com/images/new_hitchhikers_cover.jpg"
 * ),
 * @OA\Property(
 * property="author_ids",
 * type="array",
 * description="Optional array of author IDs to associate with the book. This will overwrite existing associations.",
 * @OA\Items(type="integer", format="int64", example=1),
 * nullable=true
 * ),
 * @OA\Property(
 * property="genre_ids",
 * type="array",
 * description="Optional array of genre IDs to associate with the book. This will overwrite existing associations.",
 * @OA\Items(type="integer", format="int64", example=1),
 * nullable=true
 * )
 * )
 */
class UpdateBookRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'synopsis' => ['sometimes', 'string'],
            'isbn' => ['sometimes', 'string', 'max:255'],
            'published_at' => ['sometimes', 'date'],
            'pages_amount' => ['sometimes', 'integer', 'min:1'],
            'chapters_amount' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
