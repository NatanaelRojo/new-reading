<?php

namespace App\Http\Requests\API\V1\Review;

use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class StoreReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => [
                $this->route('book') ? 'nullable' : 'required',
                'integer',
                'exists:' . Book::class . ',id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated();
        $validatedData = Arr::add($validatedData, 'user_id', $this->user()->id);

        if ($this->route('book')) {
            $validatedData = Arr::add($validatedData, 'book_id', $this->route('book')->id);
        }

        return $validatedData;
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
