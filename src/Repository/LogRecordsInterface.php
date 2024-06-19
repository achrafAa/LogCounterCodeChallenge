<?php

namespace App\Repository;

use App\Dto\LogLineDto;

interface LogRecordsInterface
{
    public function getLogRecordsCount(array $filters = []): int;

    public function addLogRecord(LogLineDto $logLineDto): bool;
}
