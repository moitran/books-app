<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class NotFoundException extends Exception
{
    abstract public function errorMessage(): string;

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->errorMessage(),
        ], JsonResponse::HTTP_NOT_FOUND);
    }
}
