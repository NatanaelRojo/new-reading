<?php

namespace App\Http\Requests\API\V1\Post;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Book;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class StorePostRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:5'],
            'progress' => ['required', 'integer', 'min:1',],
            'book_id' => $this->route('book') ? ['nullable', 'string'] : ['required', 'integer', 'exists:' . Book::class . ',id'],
        ];
    }

    /**
     * Get the validated data from the request.
     * @param mixed $key
     * @param mixed $default
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated();
        $validatedData = Arr::add($validatedData, 'user_id', $this->user()->id);

        if ($this->route('book')) {
            $validatedData = Arr::add($validatedData, 'book_id', $this->route('book')->id);
        }

        return $validatedData;
    }
}
