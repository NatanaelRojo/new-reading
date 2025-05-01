<?php

namespace App\Exceptions\API\V1\Like;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AlreadyDislikedException extends HttpException
{
    public function __construct(string $message = 'Already disliked')
    {
        parent::__construct(Response::HTTP_CONFLICT, $message);
    }
}
