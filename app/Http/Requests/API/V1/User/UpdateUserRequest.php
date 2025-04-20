<?php

namespace App\Http\Requests\API\V1\User;

use App\Http\Requests\Base\BaseApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_image_url' => ['url'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'birth_date' => ['date'],
            'biography' => ['string'],
            'name' => ['string', 'max:255'],
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->user()->id),
                'string',
                'email',
                'max:255'
            ],
        ];
    }
}
