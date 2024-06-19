<?php

namespace App\MessageHandler;

use App\Dto\LogLineDto;
use App\Message\RawLogLineMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RawLogLineMessageParsingHandler
{
    public function __invoke(RawLogLineMessage $message): void
    {
        $rawLine = $message->getLine();
        $parsedLine = $this->parseLogLine($rawLine);
    }

    private function parseLogLine(string $line): ?LogLineDto
    {
        $pattern = '/(?P<serviceName>[\w\-]+) - - \[(?P<date>[^\]]+)\] "[^"]*" (?P<statusCode>\d+)/';

        if (preg_match($pattern, $line, $matches)) {
            $serviceName = $matches['serviceName'];
            $date = \DateTime::createFromFormat('d/M/Y:H:i:s O', $matches['date']);
            $statusCode = (int) $matches['statusCode'];

            return new LogLineDto($serviceName, $date, $statusCode);
        }

        return null;
    }
}
