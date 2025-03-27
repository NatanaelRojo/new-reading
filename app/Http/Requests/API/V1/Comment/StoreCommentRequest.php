<?php

namespace App\Http\Requests\API\V1\Comment;

use App\Models\API\V1\Book;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class StoreCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:5', 'max:100'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validatedData = Arr::add(parent::validated(), 'user_id', $this->user()->id);
        $validatedData = Arr::add($validatedData, 'commentable_id', $this->route('post')->id);
        $validatedData = Arr::add($validatedData, 'commentable_type', $this->route('post')::class);

        return $validatedData;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
