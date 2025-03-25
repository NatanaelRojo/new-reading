<?php

namespace App\Http\Requests\API\V1\Book;

use App\Models\API\V1\Tag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:255'],
            'synopsis' => ['string'],
            'isbn' => ['string', 'max:255'],
            'published_at' => ['date'],
            'pages_amount' => ['integer', 'min:1'],
            'pages_read' => ['integer', 'min:1'],
            'chapters_amount' => ['integer', 'min:1'],
            'tag_id' => ['integer', 'exists:' . Tag::class . ',id'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
