<?php

namespace App\Service;

use App\Dto\LogLineDto;
use App\Message\LogLineMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PersistLogLineDispatchService
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(LogLineDto $logLineDto): void
    {
        $this->bus->dispatch(new LogLineMessage($logLineDto));
    }
}
