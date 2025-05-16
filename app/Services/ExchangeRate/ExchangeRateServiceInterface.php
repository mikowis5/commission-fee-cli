<?php

namespace App\Services\ExchangeRate;

use App\Enums\Currency;

interface ExchangeRateServiceInterface
{
    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): float;
}
