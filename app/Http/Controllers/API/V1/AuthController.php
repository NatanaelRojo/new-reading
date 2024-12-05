<?php

namespace App\Http\Controllers\API\V1;

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
        $user = User::query()
            ->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
        ], JsonResponse::HTTP_CREATED);
    }

    public function login(Request $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
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
