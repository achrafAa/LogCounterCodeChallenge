<?php

namespace App\Message;

use App\Dto\LogLineDto;

final class LogLineMessage
{
    public function __construct(
        private LogLineDto $logLineDto
    ) {
    }

    public function getLogLineDto(): LogLineDto
    {
        return $this->logLineDto;
    }
}
