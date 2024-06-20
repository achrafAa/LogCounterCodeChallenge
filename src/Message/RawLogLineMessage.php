<?php

declare(strict_types=1);

namespace App\Message;

final class RawLogLineMessage
{
    public function __construct(
        private string $line
    ) {
    }

    public function getLine(): string
    {
        return $this->line;
    }
}
