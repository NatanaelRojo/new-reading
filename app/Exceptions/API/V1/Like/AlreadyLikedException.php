<?php

namespace App\Exceptions\API\V1\Like;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AlreadyLikedException extends HttpException
{
    public function __construct(string $message = 'Already liked')
    {
        parent::__construct(Response::HTTP_CONFLICT, $message);
    }
}
