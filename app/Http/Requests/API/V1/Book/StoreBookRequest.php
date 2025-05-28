<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreBookRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['required', 'string'],
            'isbn' => ['required', 'string', 'max:255'],
            'pages_amount' => ['required', 'integer', 'min:1'],
            'chapters_amount' => ['required', 'integer', 'min:1'],
            'published_at' => ['required', 'date'],
        ];
    }
}
