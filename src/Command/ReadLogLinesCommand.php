<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\RawLogLineMessage;
use App\Service\ReadNextUnprocessedLineService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'log-counter:read-new-line',
    description: 'Reads new line from log file',
)]
class ReadLogLinesCommand extends Command
{
    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly ReadNextUnprocessedLineService $readNextUnprocessedLineService,
        private readonly MessageBusInterface $bus,
    ) {
        parent::__construct();
    }

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('reading a new line from log file command triggered ');

        $filename = $this->params->get('log.filename');
        $filePath = sprintf('%s/var/%s', $this->params->get('kernel.project_dir'), $filename);

        $io->info('checking file existence ');

        if (! file_exists($filePath)) {
            $io->error("The file {$filename} does not exist.");
            return Command::FAILURE;
        }

        $io->info('file found, Getting record');

        $positionFilename = $this->params->get('log.position_filename');
        $positionFilePath = sprintf('%s/var/%s', $this->params->get('kernel.project_dir'), $positionFilename);

        while (true) {
            if (! $line = $this->readNextUnprocessedLineService->read($filePath, $positionFilePath)) {
                $io->info('no new line found');

                break;
            }

            $this->bus->dispatch(new RawLogLineMessage($line));
        }

        return Command::SUCCESS;
    }
}
