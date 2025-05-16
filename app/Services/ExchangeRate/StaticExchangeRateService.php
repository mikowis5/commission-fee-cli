<?php

namespace App\Services\ExchangeRate;

use App\Enums\Currency;

class StaticExchangeRateService implements ExchangeRateServiceInterface
{
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): float
    {
        if ($toCurrency !== Currency::EUR) {
            throw new \InvalidArgumentException('Only EUR is supported as a target currency.');
        }

        return match ($fromCurrency) {
            Currency::EUR => 1.0,
            Currency::USD => 1/1.1497,
            Currency::JPY => 1/129.53,
        };
    }
}
