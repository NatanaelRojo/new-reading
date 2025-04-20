<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class FilterBookRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'nullable', 'string'],
            'author_name' => ['sometimes', 'nullable', 'string'],
            'genre_name' => ['sometimes', 'nullable', 'string'],
            'tag_name' => ['sometimes', 'nullable', 'string'],
            'year' => ['sometimes', 'nullable', 'digits:4', 'integer', 'min:1000', 'max:' . now()->year],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
