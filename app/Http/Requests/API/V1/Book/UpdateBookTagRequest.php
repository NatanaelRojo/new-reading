<?php

namespace App\Http\Requests\API\V1\Book;

use App\Http\Requests\Base\BaseApiRequest;
use App\Models\API\V1\Tag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateBookTagRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tag_id' => ['required', 'integer', 'exists:' . Tag::class . ',id'],
        ];
    }
}
