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
}
