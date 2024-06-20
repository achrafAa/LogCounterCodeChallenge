<?php

namespace App\Repository;

use App\Dto\LogFilterDto;
use App\Dto\LogLineDto;

interface LogRecordsInterface
{
    public function getLogRecordsCount(LogFilterDto $logFilters): int;

    public function addLogRecord(LogLineDto $logLineDto): void;
}
