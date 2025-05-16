<?php

namespace App\Dto;

use App\Enums\Currency;
use App\Enums\OperationType;
use App\Enums\UserType;
use Illuminate\Support\Carbon;

readonly class OperationDto
{
    public function __construct(
        public Carbon $date,
        public int $userId,
        public UserType $userType,
        public OperationType $operationType,
        public float $amount,
        public Currency $currency,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: Carbon::parse($data[0]),
            userId: (int) $data[1],
            userType: UserType::from($data[2]),
            operationType: OperationType::from($data[3]),
            amount: (float) $data[4],
            currency: Currency::from($data[5]),
        );
    }
}
