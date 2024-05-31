<?php

namespace App\Exceptions;

class BookNotFoundException extends NotFoundException
{
    public function errorMessage(): string
    {
        return 'The Book does not found';
    }
}
