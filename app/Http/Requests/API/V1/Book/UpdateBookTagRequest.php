<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Tag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 * schema="UpdateBookTagRequest",
 * title="Update Book Tag Request",
 * description="Request body for updating a user's tag association for a specific book.",
 * required={"tag_id"},
 * @OA\Property(
 * property="tag_id",
 * type="integer",
 * format="int64",
 * description="The ID of the tag to associate with the book for the current user. Must be an existing tag ID.",
 * example=1
 * )
 * )
 */
class UpdateBookTagRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tag_id' => ['required', 'integer', 'exists:' . Tag::class . ',id'],
        ];
    }
}
