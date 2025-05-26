<?php

namespace App\Http\Requests\API\V1\Author;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 * schema="StoreAuthorRequest",
 * title="Store Author Request",
 * description="Request body for creating a new author",
 * required={"first_name", "last_name", "nationality", "biography", "image_url"},
 * @OA\Property(
 * property="first_name",
 * type="string",
 * description="The first name of the author",
 * maxLength=255,
 * example="John"
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * description="The last name of the author",
 * maxLength=255,
 * example="Doe"
 * ),
 * @OA\Property(
 * property="nationality",
 * type="string",
 * description="The nationality of the author",
 * maxLength=255,
 * example="American"
 * ),
 * @OA\Property(
 * property="biography",
 * type="string",
 * description="A short biography of the author",
 * example="John Doe is a prolific writer known for his captivating sci-fi novels."
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * format="url",
 * description="URL to the author's profile image",
 * example="https://example.com/images/john_doe.jpg"
 * )
 * )
 */
class StoreAuthorRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or your actual authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'biography' => ['required', 'string'],
            'image_url' => ['required', 'url'],
        ];
    }
}
