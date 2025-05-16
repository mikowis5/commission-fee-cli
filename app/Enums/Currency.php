<?php

namespace App\Enums;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case JPY = 'JPY';

    public function precision(): int
    {
        return match($this) {
            self::JPY => 0,
            self::USD, self::EUR => 2,
        };
    }
}
