<?php

namespace App\Services;

use App\Dto\OperationDto;
use Iterator;
use League\Csv\Reader;

class OperationsFileReader
{
    private Iterator $operations;

    public function readCsv(string $filePath): self
    {
        $this->operations = Reader::createFromPath($filePath)
            ->setDelimiter(',')
            ->getRecords();

        return $this;
    }

    public function each(callable $callback): void
    {
        foreach ($this->operations as $operation) {
            $callback(OperationDto::fromArray($operation));
        }
    }
}
