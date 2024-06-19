<?php

namespace App\Command;

use App\Service\ParseRawLogLineDispatchService;
use App\Service\ReadNextUnprocessedLineService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsCommand(
    name: 'log-counter:read-new-line',
    description: 'Reads new line from log file',
)]
class ReadLogLineCommand extends Command
{
    private ParameterBagInterface $params;

    private ReadNextUnprocessedLineService $readNextUnprocessedLineService;

    private ParseRawLogLineDispatchService $parseRawLogLineDispatchService;

    public function __construct(
        ParameterBagInterface $params,
        ReadNextUnprocessedLineService $readNextUnprocessedLineService,
        ParseRawLogLineDispatchService $parseRawLogLineDispatchService
    ) {
        parent::__construct();
        $this->params = $params;
        $this->readNextUnprocessedLineService = $readNextUnprocessedLineService;
        $this->parseRawLogLineDispatchService = $parseRawLogLineDispatchService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('reading a new line from log file command triggered ');

        $fileName = $this->params->get('env(LOG_FILE_NAME)');
        $filePath = sprintf('%s/var/%s', $this->params->get('kernel.project_dir'), $fileName);

        $positionFileNAME = $this->params->get('env(POSITION_FILE_NAME)');
        $positionFilePath = sprintf('%s/var/%s', $this->params->get('kernel.project_dir'), $positionFileNAME);

        $io->info('checking file existence ');

        if (! file_exists($filePath)) {
            $io->error("The file {$fileName} does not exist.");
            return Command::FAILURE;
        }

        $io->info('file found, Getting record');

        $line = $this->readNextUnprocessedLineService->read($filePath, $positionFilePath);

        if (! $line) {
            $io->info('no new line found');
            return Command::SUCCESS;
        }
        try {
            $this->parseRawLogLineDispatchService->dispatch($line);
        } catch (ExceptionInterface $e) {
            $io->error('an error occurred while dispatching parse message' . $e->getMessage());
        }


        return Command::SUCCESS;
    }
}