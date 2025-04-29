<?php

namespace App\Exceptions\API\V1\User;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;

class UserNotFollowingException extends AuthorizationException
{
    protected $message = 'You must follow the author to comment on their post.';
}
