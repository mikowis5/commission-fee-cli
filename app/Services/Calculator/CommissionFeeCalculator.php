<?php

namespace App\Services\Calculator;

use App\Dto\OperationDto;
use App\Enums\Currency;
use App\Enums\OperationType;
use App\Enums\UserType;
use App\Services\ExchangeRate\ExchangeRateServiceInterface;

class CommissionFeeCalculator implements CommissionFeeCalculatorInterface
{
    const DEPOSIT_FEE_FACTOR = 0.03 / 100;
    const WITHDRAW_BUSINESS_FEE_FACTOR = 0.5 / 100;
    const WITHDRAW_PRIVATE_FEE_FACTOR = 0.3 / 100;

    public function __construct(
        private PrivateWithdrawalTracker $withdrawalTracker,
    ) {}

    public function calculateForOperation(OperationDto $operation): float
    {
        $fee = $this->calculate($operation);

        return $this->round($fee, $operation->currency->precision());
    }

    private function calculate(OperationDto $operation): float
    {
        if ($operation->operationType === OperationType::DEPOSIT) {
            return $operation->amount * self::DEPOSIT_FEE_FACTOR;
        }

        if ($operation->userType === UserType::BUSINESS) {
            return $operation->amount * self::WITHDRAW_BUSINESS_FEE_FACTOR;
        }

        $amountInEur = $operation->amount * app(ExchangeRateServiceInterface::class)->getExchangeRate(
            $operation->currency,
            Currency::EUR
        );

        $this->withdrawalTracker->trackWithdrawal($operation, $amountInEur);
        $chargeableAmountInEur = $this->withdrawalTracker->getChargeableAmount($operation, $amountInEur);

        if ($chargeableAmountInEur === 0) {
            return 0;
        }

        $chargeableAmount = $chargeableAmountInEur / app(ExchangeRateServiceInterface::class)->getExchangeRate(
            $operation->currency,
            Currency::EUR
        );

        return $chargeableAmount * self::WITHDRAW_PRIVATE_FEE_FACTOR;
    }

    private function round(float $value, int $precision): float
    {
        $factor = pow(10, $precision);

        return ceil($value * $factor) / $factor;
    }
}
