<?php

namespace App\Http\Requests\API\V1\Author;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule; // Don't forget to use Rule if you're ignoring a unique ID

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
