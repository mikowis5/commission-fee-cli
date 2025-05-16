<?php

namespace App\Services\Calculator;

use App\Dto\OperationDto;

class PrivateWithdrawalTracker
{
    private array $weeklyWithdrawals = [];
    private const WEEKLY_FREE_AMOUNT_EUR = 1000.00;
    private const FREE_OPERATIONS_PER_WEEK = 3;

    public function trackWithdrawal(OperationDto $operation, float $amountInEur): void
    {
        $weekKey = $operation->date->startOfWeek()->format('Y-m-d');

        if (!isset($this->weeklyWithdrawals[$operation->userId][$weekKey])) {
            $this->weeklyWithdrawals[$operation->userId][$weekKey] = [
                'operations' => 0,
                'amount' => 0,
            ];
        }

        $this->weeklyWithdrawals[$operation->userId][$weekKey]['operations']++;
        $this->weeklyWithdrawals[$operation->userId][$weekKey]['amount'] += $amountInEur;
    }

    public function getChargeableAmount(OperationDto $operation, float $amountInEur): float
    {
        $weekKey = $operation->date->startOfWeek()->format('Y-m-d');
        $weekly = $this->weeklyWithdrawals[$operation->userId][$weekKey] ?? ['operations' => 0, 'amount' => 0];

        if ($weekly['operations'] > self::FREE_OPERATIONS_PER_WEEK) {
            return $amountInEur;
        }

        $previousAmount = $weekly['amount'] - $amountInEur;
        if ($previousAmount >= self::WEEKLY_FREE_AMOUNT_EUR) {
            return $amountInEur;
        }

        return max(0, $previousAmount + $amountInEur - self::WEEKLY_FREE_AMOUNT_EUR);
    }
}
