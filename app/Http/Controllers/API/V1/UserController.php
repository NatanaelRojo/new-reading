<?php

namespace App\Http\Controllers\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\User\StoreUserDTO;
use App\DataTransferObjects\API\V1\User\UpdateUserDTO;
use App\Http\Requests\API\V1\Paginate\PaginateRequest;
use App\Http\Requests\API\V1\User\StoreUserRequest;
use App\Http\Requests\API\V1\User\UpdateUserRequest;
use App\Http\Resources\API\V1\User\UserResource;
use App\Models\User;
use App\Services\API\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginateRequest $request): AnonymousResourceCollection
    {
        $paginateDto = PaginateDTO::fromRequest($request);

        $users = $this->userService->index($paginateDto);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $storeUserDDto = StoreUserDTO::fromRequest($request);

        $newUser = $this->userService->store($storeUserDDto);

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
        $updateUserDto = UpdateUserDTO::fromRequest($request);

        $this->userService->update($updateUserDto, $user);

        return response()->json(new UserResource($user), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userService->destroy($user);

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
        $this->userService->follow($request->user(), $user);

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
        $this->userService->unfollow($request->user(), $user);

        return response()->json([
            'message' => "You have unfollowed {$user->name}."
        ], JsonResponse::HTTP_OK);
    }
}
