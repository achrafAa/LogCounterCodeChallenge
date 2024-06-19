<?php

namespace App\MessageHandler;

use App\Dto\LogLineDto;
use App\Message\RawLogLineMessage;
use App\Service\PersistLogLineDispatchService;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsMessageHandler]
final class RawLogLineMessageParseHandler
{
    private PersistLogLineDispatchService $persistLogLineDispatchService;

    public function __construct(PersistLogLineDispatchService $persistLogLineDispatchService)
    {
        $this->persistLogLineDispatchService = $persistLogLineDispatchService;
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(RawLogLineMessage $message): void
    {
        $rawLine = $message->getLine();
        $parsedLine = $this->parseLogLine($rawLine);
        $this->persistLogLineDispatchService->dispatch($parsedLine);
    }

    private function parseLogLine(string $line): ?LogLineDto
    {
        $pattern = '/(?P<serviceName>[\w\-]+) - - \[(?P<date>[^\]]+)\] "[^"]*" (?P<statusCode>\d+)/';

        if (preg_match($pattern, $line, $matches)) {
            $serviceName = $matches['serviceName'];
            $date = DateTimeImmutable::createFromFormat('d/M/Y:H:i:s O', $matches['date']);
            $statusCode = (int) $matches['statusCode'];

            return new LogLineDto($serviceName, $date, $statusCode);
        }

        return null;
    }
}
