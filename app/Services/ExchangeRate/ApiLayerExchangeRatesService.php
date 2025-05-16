<?php

namespace App\Services\ExchangeRate;

use App\Enums\Currency;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ApiLayerExchangeRatesService implements ExchangeRateServiceInterface
{
    const BASE_URL = 'https://api.exchangeratesapi.io/latest';
    const CACHE_DURATION = 3600 * 24; // 1 day

    private Client $client;
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client;
    }

    public function getExchangeRate(Currency $fromCurrency, Currency $toCurrency): float
    {
        if ($toCurrency !== Currency::EUR) {
            throw new \InvalidArgumentException('Only EUR is supported as a target currency.');
        }

        $rates = $this->fetchRates();

        if (!isset($rates[$fromCurrency->value])) {
            throw new \InvalidArgumentException('Exchange rate not found for the given currency.');
        }

        return 1 / $rates[$fromCurrency->value];
    }

    private function fetchRates(): array
    {
        return Cache::remember('exchange_rates', self::CACHE_DURATION, function () {
            $response = $this->client->get(self::BASE_URL . '?access_key=' . $this->apiKey);

            $data = json_decode($response->getBody(), true);

            if (isset($data['error'])) {
                throw new \RuntimeException('Error fetching exchange rates: ' . $data['error']['info']);
            }

            return $data['rates'];
        });
    }
}
