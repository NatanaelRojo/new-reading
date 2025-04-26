<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\User\LoginUserDTO;
use App\DataTransferObjects\API\V1\User\RegisterUserDTO;
use App\Http\Requests\API\V1\User\LoginUserRequest;
use App\Http\Requests\API\V1\User\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $registerUserDto = new RegisterUserDTO(...$request->validated());

        $user = User::query()
            ->create($registerUserDto->toArray());

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
        ], JsonResponse::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $loginUserDto = new LoginUserDTO(...$request->vlidated());

        $user = User::query()
            ->where('email', $loginUserDto->email)
            ->first();

        if (!$user || !Hash::check($loginUserDto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], JsonResponse::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
