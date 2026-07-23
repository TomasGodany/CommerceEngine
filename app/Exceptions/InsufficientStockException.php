<?php

namespace App\Exceptions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $productName)
    {
        parent::__construct("Nedostatočné množstvo na sklade pre produkt: {$productName}.");
    }
}
