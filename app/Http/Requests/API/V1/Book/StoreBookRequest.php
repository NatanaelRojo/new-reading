<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 * schema="StoreBookRequest",
 * title="Store Book Request",
 * description="Request body for creating a new Book. All fields are required as per validation rules.",
 * required={"title", "synopsis", "isbn", "pages_amount", "chapters_amount", "published_at"},
 * @OA\Property(
 * property="title",
 * type="string",
 * description="Title of the book",
 * maxLength=255,
 * example="The Secret Garden"
 * ),
 * @OA\Property(
 * property="synopsis",
 * type="string",
 * description="A brief summary or outline of the book's plot.",
 * example="A classic story about a young girl who discovers a hidden garden."
 * ),
 * @OA\Property(
 * property="isbn",
 * type="string",
 * description="International Standard Book Number (unique identifier)",
 * maxLength=255,
 * example="978-1234567890"
 * ),
 * @OA\Property(
 * property="pages_amount",
 * type="integer",
 * format="int32",
 * description="Total number of pages in the book",
 * minimum=1,
 * example=288
 * ),
 * @OA\Property(
 * property="chapters_amount",
 * type="integer",
 * format="int32",
 * description="Total number of chapters in the book",
 * minimum=1,
 * example=27
 * ),
 * @OA\Property(
 * property="published_at",
 * type="string",
 * format="date",
 * description="The date the book was first published (YYYY-MM-DD)",
 * example="1911-08-01"
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * format="url",
 * description="Optional URL to the book's cover image",
 * nullable=true,
 * example="https://example.com/images/secret_garden_cover.jpg"
 * ),
 * @OA\Property(
 * property="author_ids",
 * type="array",
 * description="Optional array of author IDs to associate with the book",
 * @OA\Items(type="integer", format="int64", example=1),
 * nullable=true
 * ),
 * @OA\Property(
 * property="genre_ids",
 * type="array",
 * description="Optional array of genre IDs to associate with the book",
 * @OA\Items(type="integer", format="int64", example=1),
 * nullable=true
 * )
 * )
 */
class StoreBookRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['required', 'string'],
            'isbn' => ['required', 'string', 'max:255'],
            'pages_amount' => ['required', 'integer', 'min:1'],
            'chapters_amount' => ['required', 'integer', 'min:1'],
            'published_at' => ['required', 'date'],
        ];
    }
}
