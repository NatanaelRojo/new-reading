<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Tag;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'synopsis' => ['sometimes', 'string'],
            'isbn' => ['sometimes', 'string', 'max:255'],
            'published_at' => ['sometimes', 'date'],
            'pages_amount' => ['sometimes', 'integer', 'min:1'],
            'chapters_amount' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
