<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 * schema="UpdateBookReadingProgressRequest",
 * title="Update Book Reading Progress Request",
 * description="Request body for updating a user's reading progress on a specific book.",
 * required={"pages_read"},
 * @OA\Property(
 * property="pages_read",
 * type="integer",
 * format="int32",
 * description="The current page number the user has read up to for this book.",
 * minimum=1,
 * example=150
 * )
 * )
 */
class UpdateBookReadingProgressRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pages_read' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
