<?php

namespace App\Enums;

enum OperationType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdraw';
}
