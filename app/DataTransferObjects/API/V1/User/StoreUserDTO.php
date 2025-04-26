<?php

namespace App\DataTransferObjects\API\V1\User;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for StoreUser
 *
 * Auto-generated from App\Http\Requests\API\V1\User\StoreUserRequest.
 */
class StoreUserDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $profile_image_url,
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly \Carbon\Carbon|string $birth_date,
        public readonly string $biography,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $password_confirmation,
    ) {
    }
}
