<?php

namespace App\DataTransferObjects\API\V1\User;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for LoginUser
 *
 * Auto-generated from App\Http\Requests\API\V1\User\LoginUserRequest.
 */
class LoginUserDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
