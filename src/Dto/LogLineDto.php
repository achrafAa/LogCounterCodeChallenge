<?php

namespace App\Dto;

use DateTimeImmutable;

class LogLineDto
{
    private string $serviceName;

    private DateTimeImmutable $date;

    private int $statusCode;

    public function __construct(string $serviceName, DateTimeImmutable $date, int $statusCode)
    {
        $this->serviceName = $serviceName;
        $this->date = $date;
        $this->statusCode = $statusCode;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
