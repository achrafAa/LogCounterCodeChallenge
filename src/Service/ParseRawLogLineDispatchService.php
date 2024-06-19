<?php

namespace App\Service;

use App\Message\RawLogLineMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ParseRawLogLineDispatchService
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(string $rawLogLine): void
    {
        $this->bus->dispatch(new RawLogLineMessage($rawLogLine));
    }
}
