<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;

readonly class LogLineDto
{
    public function __construct(
        public string $serviceName,
        public DateTimeImmutable $date,
        public int $statusCode
    ) {
    }
}
