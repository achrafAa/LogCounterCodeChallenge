<?php

namespace App\Message;

use App\Dto\LogLineDto;

class LogLineMessage
{
    private LogLineDto $logLineDto;

    public function __construct(LogLineDto $logLineDto)
    {
        $this->logLineDto = $logLineDto;
    }

    public function getLogLineDto(): LogLineDto
    {
        return $this->logLineDto;
    }
}
