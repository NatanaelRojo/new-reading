<?php

namespace App\Services\API\V1;

use App\DataTransferObjects\API\V1\Paginate\PaginateDTO;
use App\DataTransferObjects\API\V1\User\StoreUserDTO;
use App\DataTransferObjects\API\V1\User\UpdateUserDTO;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service for UserService
 */
class UserService
{
    /**
     * Get all instances from the database
     */
    public function index(PaginateDTO $paginateDto): LengthAwarePaginator
    {
        return User::with(['following', 'followers'])
        ->paginate($paginateDto->perPage ?? 10);
    }

    /**
     * Store a new instance in the database
     */
    public function store(StoreUserDTO $storeUserDto): User
    {
        return User::query()
        ->create($storeUserDto->toArray());
    }

    /**
     * Get a single instance from the database
     */
    public function show()
    {
        //
    }

    /**
     * Update an existing instance in the database
     */
    public function update(UpdateUserDTO $updateUserDto, User $user): void
    {
        $user->update($updateUserDto->toArray());
    }

    /**
     * Delete an existing instance from the database
     */
    public function destroy(User $user): void
    {
        $user->delete();
    }

    public function follow(User $currentUser, User $userToFollow): void
    {
        if (!$currentUser->isFollowing($userToFollow)) {
            $currentUser->follow($userToFollow);
        }
    }

    public function unfollow(User $currentUser, User $userToUnfollow): void
    {
        if ($currentUser->isFollowing($userToUnfollow)) {
            $currentUser->unfollow($userToUnfollow);
        }
    }
}
