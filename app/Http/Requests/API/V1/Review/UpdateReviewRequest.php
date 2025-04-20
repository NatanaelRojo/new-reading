<?php

namespace App\Http\Requests\API\V1\Review;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Book;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class UpdateReviewRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => ['integer', 'exists:' . Book::class . ',id'],
            'rating' => ['integer', 'min:1', 'max:5'],
            'comment' => ['string'],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param mixed $key
     * @param mixed $default
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        return Arr::add(parent::validated(), 'user_id', $this->user()->id);
    }
}
