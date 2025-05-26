<?php

namespace App\Http\Requests\API\V1\Author;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule; // Don't forget to use Rule if you're ignoring a unique ID

/**
 * @OA\Schema(
 * schema="UpdateAuthorRequest",
 * title="Update Author Request",
 * description="Request body for updating an existing author. All fields are optional, providing only the ones to be updated.",
 * @OA\Property(
 * property="first_name",
 * type="string",
 * description="The updated first name of the author",
 * maxLength=255,
 * example="Janet"
 * ),
 * @OA\Property(
 * property="last_name",
 * type="string",
 * description="The updated last name of the author",
 * maxLength=255,
 * example="Smith"
 * ),
 * @OA\Property(
 * property="nationality",
 * type="string",
 * description="The updated nationality of the author",
 * maxLength=255,
 * example="British"
 * ),
 * @OA\Property(
 * property="biography",
 * type="string",
 * description="An updated biography of the author",
 * example="Janet Smith is a celebrated author known for her historical fiction."
 * ),
 * @OA\Property(
 * property="image_url",
 * type="string",
 * format="url",
 * description="Updated URL to the author's profile image",
 * example="https://example.com/images/janet_smith_new.jpg"
 * )
 * )
 */
class UpdateAuthorRequest extends BaseApiRequest
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
        // If you were using a unique rule that needed to ignore the current author,
        // you would uncomment the following line and pass $authorId to the unique rule:
        // $authorId = $this->route('author') ? $this->route('author')->id : null;

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'nationality' => ['sometimes', 'string', 'max:255'],
            'biography' => ['sometimes', 'string'],
            'image_url' => ['sometimes', 'string', 'url'],
            // Example of a unique rule that ignores the current author (if 'email' was here):
            // 'email' => ['sometimes', 'email', Rule::unique('authors')->ignore($authorId)],
        ];
    }
}
