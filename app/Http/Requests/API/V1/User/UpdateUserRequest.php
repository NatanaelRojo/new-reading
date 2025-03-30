<?php

namespace App\Http\Requests\API\V1\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
