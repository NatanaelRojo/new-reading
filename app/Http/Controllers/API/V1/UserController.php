<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\User\StoreUserDTO;
use App\DataTransferObjects\API\V1\User\UpdateUserDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\User\StoreUserRequest;
use App\Http\Requests\API\V1\User\UpdateUserRequest;
use App\Http\Resources\API\V1\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $users = User::with(['following', 'followers'])
            ->paginate($request->query('per_page', 10));

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $storeUserDDto = new StoreUserDTO(...$request->validated());

        $newUser = User::query()
            ->create($storeUserDDto->toArray());

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
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updateUserDto = new UpdateUserDTO(...$request->validated());

        $user->update($updateUserDto->toArray());

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

    /**
     * Follow a user if not already following.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return JsonResponse|mixed
     */
    public function follow(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if (!$user->isFollowing($authUser)) {
            $authUser->follow($user);
        }

        return response()->json([
            'message' => "You are now following {$user->name}."
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Unfollow a user if already following.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return JsonResponse|mixed
     */
    public function unfollow(Request $request, User $user): JsonResponse
    {
        $authUser = $request->user();

        if ($authUser->isFollowing($user)) {
            $authUser->unfollow($user);
        }

        return response()->json([
            'message' => "You have unfollowed {$user->name}."
        ], JsonResponse::HTTP_OK);
    }
}
