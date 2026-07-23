<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidCouponException extends RuntimeException
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Zadaný kupón nie je platný.')
    {
        parent::__construct($message);
    }
}
