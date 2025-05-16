<?php

namespace App\Services\Calculator;

use App\Dto\OperationDto;

interface CommissionFeeCalculatorInterface
{
    public function calculateForOperation(OperationDto $operation): float;
}
