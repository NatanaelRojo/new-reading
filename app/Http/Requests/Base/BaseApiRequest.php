<?php

namespace App\Http\Requests\Base;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

abstract class BaseApiRequest extends FormRequest
{
    /**
 * Handle a failed validation attempt.
 *
 * @param \Illuminate\Contracts\Validation\Validator $validator
 * @throws \Illuminate\Http\Exceptions\HttpResponseException
 * @return void
 */
    protected function failedValidation(Validator $validator): void
    {
        if (!app()->runningInConsole()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
    }
}
