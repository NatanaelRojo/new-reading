<?php

namespace App\DataTransferObjects\API\V1\User;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for RegisterUser
 *
 * Auto-generated from App\Http\Requests\API\V1\User\RegisterUserRequest.
 */
class RegisterUserDTO extends BaseApiDTO
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
    ) {
    }
}
