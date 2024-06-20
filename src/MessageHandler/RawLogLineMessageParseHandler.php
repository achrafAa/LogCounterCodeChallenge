<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Dto\LogLineDto;
use App\Message\LogLineMessage;
use App\Message\RawLogLineMessage;
use DateTimeImmutable;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class RawLogLineMessageParseHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }
    /**
     * @throws ExceptionInterface
     */
    public function __invoke(RawLogLineMessage $message): void
    {
        $this->messageBus
            ->dispatch(new LogLineMessage(
                $this->parseLogLine(
                    $message->getLine(),
                )
            ));
    }
    private function parseLogLine(string $line): LogLineDto
    {
        $pattern = '/(?P<serviceName>[\w\-]+) - - \[(?P<date>[^\]]+)\] "[^"]*" (?P<statusCode>\d+)/';

        if (preg_match($pattern, $line, $matches)) {
            $serviceName = $matches['serviceName'];
            $date = DateTimeImmutable::createFromFormat('d/M/Y:H:i:s O', $matches['date']);
            $statusCode = (int) $matches['statusCode'];

            return new LogLineDto($serviceName, $date, $statusCode);
        }

        throw new \RuntimeException('Invalid log line format');
    }
}
