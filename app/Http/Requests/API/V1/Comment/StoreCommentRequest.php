<?php

namespace App\Http\Requests\API\V1\Comment;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Book;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class StoreCommentRequest extends BaseApiRequest
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

    /**
     * Get the validated data from the request.
     *
     * @param mixed $key
     * @param mixed $default
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        $validatedData = Arr::add(parent::validated(), 'user_id', $this->user()->id);

        if ($this->has('post')) {
            $validatedData = Arr::add($validatedData, 'commentable_id', $this->route('post')->id);
            $validatedData = Arr::add($validatedData, 'commentable_type', $this->route('post')::class);
        } elseif ($this->has('review')) {
            $validatedData = Arr::add($validatedData, 'commentable_id', $this->route('review')->id);
            $validatedData = Arr::add($validatedData, 'commentable_type', $this->route('review')::class);
        }

        return $validatedData;
    }
}
