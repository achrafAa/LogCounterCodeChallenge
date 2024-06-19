<?php

namespace App\Message;

final class RawLogLineMessage
{
    private string $line;

    public function __construct(string $line)
    {
        $this->line = $line;
    }

    public function getLine(): string
    {
        return $this->line;
    }
}
