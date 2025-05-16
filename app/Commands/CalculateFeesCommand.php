<?php

namespace App\Commands;

use App\Dto\OperationDto;
use App\Services\Calculator\CommissionFeeCalculatorInterface;
use App\Services\OperationsFileReader;
use LaravelZero\Framework\Commands\Command;

class CalculateFeesCommand extends Command
{
    protected $signature = 'calculate-commission-fee {input-file}';

    protected $description = 'Calculate commission fee from CSV file';

    public function __construct(
        private OperationsFileReader $fileReader,
        private CommissionFeeCalculatorInterface $calculator
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $path = $this->argument('input-file');

        $this->fileReader
            ->readCsv($path)
            ->each(function (OperationDto $dto) {
                $fee = $this->calculator->calculateForOperation($dto);

                $this->line($fee, verbosity: true);
            });
    }
}
