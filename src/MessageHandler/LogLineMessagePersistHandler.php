<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Exception\PersistLogFailedException;
use App\Message\LogLineMessage;
use App\Repository\LogRecordsInterface;
use Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class LogLineMessagePersistHandler
{
    public function __construct(
        private LogRecordsInterface $logRepository,
    ) {
    }

    /**
     * @throws PersistLogFailedException
     */
    public function __invoke(LogLineMessage $logLineMessage): void
    {
        try {
            $this->logRepository
                ->addLogRecord(
                    $logLineMessage->getLogLineDto(),
                );
        } catch (Exception) {
            throw new PersistLogFailedException();
        }
    }
}
