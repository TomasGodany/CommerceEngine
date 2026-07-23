<?php

namespace App\Enums;

enum DocumentType: string
{
    case Invoice = 'invoice';
    case DeliveryNote = 'delivery_note';
    case Quote = 'quote';

    /**
     * Get the human-readable label for this document type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Invoice => 'Faktúra',
            self::DeliveryNote => 'Dodací list',
            self::Quote => 'Cenová ponuka',
        };
    }

    /**
     * Get the number prefix used for this document type.
     */
    public function prefix(): string
    {
        return match ($this) {
            self::Invoice => 'FA',
            self::DeliveryNote => 'DL',
            self::Quote => 'CP',
        };
    }
}
