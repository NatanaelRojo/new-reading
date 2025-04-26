<?php

namespace App\DataTransferObjects\API\V1\User;

use App\DataTransferObjects\Base\BaseApiDTO;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Data Transfer Object for UpdateUser
 *
 * Auto-generated from App\Http\Requests\API\V1\User\UpdateUserRequest.
 */
class UpdateUserDTO extends BaseApiDTO
{
    public function __construct(
        public readonly string $email,
        public readonly ?string $profile_image_url = null,
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly \Carbon\Carbon|string|null $birth_date = null,
        public readonly ?string $biography = null,
        public readonly ?string $name = null,
        public readonly ?string $password = null,
        public readonly ?string $password_confirmation = null,
    ) {
    }
}
