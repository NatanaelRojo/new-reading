<?php

namespace App\Exceptions\API\V1\Book;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookNotCompletedException extends HttpException
{
    public function __construct(string $message = 'Book not completed')
    {
        parent::__construct(Response::HTTP_CONFLICT, $message);
    }
}
