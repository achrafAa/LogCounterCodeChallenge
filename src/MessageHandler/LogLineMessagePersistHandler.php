<?php

namespace App\MessageHandler;

use App\Exception\PersistLogFailedException;
use App\Message\LogLineMessage;
use App\Service\PersistLogLineToDataBaseService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LogLineMessagePersistHandler
{
    private PersistLogLineToDataBaseService $persistLogLineToDataBaseService;

    public function __construct(PersistLogLineToDataBaseService $persistLogLineToDataBaseService)
    {
        $this->persistLogLineToDataBaseService = $persistLogLineToDataBaseService;
    }

    /**
     * @throws PersistLogFailedException
     */
    public function __invoke(LogLineMessage $logLineMessage): void
    {
        $logLineDto = $logLineMessage->getLogLineDto();
        if (! $this->persistLogLineToDataBaseService->persist($logLineDto)) {
            throw new PersistLogFailedException();
        }
    }
}
