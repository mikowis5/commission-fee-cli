<?php

namespace Tests\Feature;

use App\Services\ExchangeRate\ExchangeRateServiceInterface;
use App\Services\ExchangeRate\StaticExchangeRateService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CalculateFeesCommandTest extends TestCase
{
    private string $testCsvContent = <<<CSV
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY
CSV;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            ExchangeRateServiceInterface::class,
            StaticExchangeRateService::class,
        );

        Storage::fake('local');
        Storage::put('input.csv', $this->testCsvContent);
    }

    public function testCalculateFeesCommand(): void
    {
        $expectedOutput = [
            "0.6",
            "3",
            "0",
            "0.06",
            "1.5",
            "0",
            "0.7",
            "0.3",
            "0.3",
            "3",
            "0",
            "0",
            "8612",
        ];

        $res = $this->artisan('calculate-commission-fee', [
            'input-file' => Storage::path('input.csv')
        ])->assertExitCode(0);

        foreach ($expectedOutput as $line) {
            $res->expectsOutput($line);
        }
    }
}
