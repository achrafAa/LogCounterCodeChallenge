<?php

namespace App\Service;

use App\Dto\LogLineDto;
use App\Repository\LogRecordsInterface;

class PersistLogLineToDataBaseService
{
    private LogRecordsInterface $logRepository;

    public function __construct(LogRecordsInterface $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function persist(LogLineDto $logLineDto): bool
    {
        dump($this->logRepository->addLogRecord($logLineDto));
        return $this->logRepository->addLogRecord($logLineDto);
    }
}
