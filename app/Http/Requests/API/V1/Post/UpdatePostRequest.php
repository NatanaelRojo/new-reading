<?php

namespace App\Http\Requests\API\V1\Post;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Book;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class UpdatePostRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['string', 'min:5', 'max:1000'],
            'progress' => ['integer', 'min:1',],
            'book_id' => ['exists:' . Book::class . ',id'],
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
