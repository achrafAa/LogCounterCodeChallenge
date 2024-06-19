<?php

namespace App\Dto;

use DateTime;

class LogLineDto
{
    private string $serviceName;

    private DateTime $date;

    private int $statusCode;

    public function __construct(string $serviceName, DateTime $date, int $statusCode)
    {
        $this->serviceName = $serviceName;
        $this->date = $date;
        $this->statusCode = $statusCode;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
