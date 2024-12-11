<?php

namespace App\Http\Controllers\API\V1\Controllers;

use App\Http\Resources\API\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with(['following', 'followers'])
            ->get();

        return response()->json(UserResource::collection($users), JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $newUser = User::query()
            ->create($request->validated());

        return response()->json(new UserResource($newUser), JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(new UserResource($user), JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json(new UserResource($user), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    public function toggleFollow(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        if ($user->isFollowing($user)) {
            $currentUser->unfollow($user);

            return response()->json(['message' => 'Unfollowed'], JsonResponse::HTTP_OK);
        } else {
            $currentUser->follow($user);

            return response()->json(['message' => 'Followed'], JsonResponse::HTTP_OK);
        }
    }
}
