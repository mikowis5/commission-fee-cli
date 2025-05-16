<?php

namespace App\Providers;

use App\Services\Calculator\CommissionFeeCalculator;
use App\Services\Calculator\CommissionFeeCalculatorInterface;
use App\Services\ExchangeRate\ApiLayerExchangeRatesService;
use App\Services\ExchangeRate\ExchangeRateServiceInterface;
use App\Services\ExchangeRate\StaticExchangeRateService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            CommissionFeeCalculatorInterface::class,
            CommissionFeeCalculator::class
        );

        $this->app->bind(
            ExchangeRateServiceInterface::class,
            fn () => new ApiLayerExchangeRatesService(
                config('services.api_layer_exchange_rates.api_key'),
            )
        );
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
