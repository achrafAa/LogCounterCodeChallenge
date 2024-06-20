<?php

namespace App\Tests\Command;

use App\Command\ReadLogLinesCommand;
use App\Service\ReadNextUnprocessedLineService;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/***
 * @covers \App\Command\ReadLogLineCommand
 * @covers \App\Service\ReadNextUnprocessedLineService
 * @covers \App\Service\PersistLogLineDispatchService
 * @covers \App\Service\PersistLogLineToDataBaseService
 * @covers \App\Service\ParseRawLogLineDispatchService
 */
class ReadLogLinesCommandTest extends KernelTestCase
{
    use InteractsWithMessenger;
    use ReloadDatabaseTrait;
    protected function setUp(): void
    {
        parent::setUp();
        $this->bootKernel();
        $this->createTestLogFileIfDoesNotExist(self::$kernel);
    }

    public function testExecute(): void
    {
        $kernel = self::$kernel;
        $logFilePath = sprintf('%s/var/%s', $kernel->getProjectDir(), 'logs-test.log');
        $this->assertFileExists($logFilePath);

        $positionFilePath = sprintf('%s/var/%s', $kernel->getProjectDir(), 'position-test.txt');
        file_put_contents($positionFilePath, '0');
        $this->assertFileExists($positionFilePath);

        $this->transport()->queue()->assertEmpty();

        $readNextUnprocessedLineService = new ReadNextUnprocessedLineService();


        $parameterBagMock = $this->createMock(ParameterBagInterface::class);

        $parameterBagMock->method('get')->willReturnMap([
            ['kernel.project_dir', $kernel->getProjectDir()],
            ['log.filename', 'logs-test.log'],
            ['log.position_filename', 'position-test.txt'],
        ]);


        $command = new ReadLogLinesCommand(
            $parameterBagMock,
            $readNextUnprocessedLineService,
            $this->bus()
        );
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertStringContainsString('reading a new line from log file command triggered', $commandTester->getDisplay());
        $this->assertStringContainsString('file found, Getting record', $commandTester->getDisplay());

        $this->transport()->queue()->assertCount(20);
        $this->transport()->process(20);
        $this->transport()->queue()->assertCount(0);
    }

    private function createTestLogFileIfDoesNotExist(?KernelInterface $kernel): void
    {
        $logFilePath = sprintf('%s/var/%s', $kernel->getProjectDir(), 'logs-test.log');
        if (file_exists($logFilePath)) {
            return;
        }
        $data = <<<EOD
USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:21:54 +0000] "POST /users HTTP/1.1" 400
INVOICE-SERVICE - - [17/Aug/2018:09:21:55 +0000] "POST /invoices HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:21:56 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:21:57 +0000] "POST /users HTTP/1.1" 201
INVOICE-SERVICE - - [17/Aug/2018:09:22:58 +0000] "POST /invoices HTTP/1.1" 201
INVOICE-SERVICE - - [17/Aug/2018:09:22:59 +0000] "POST /invoices HTTP/1.1" 400
INVOICE-SERVICE - - [17/Aug/2018:09:23:53 +0000] "POST /invoices HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:23:54 +0000] "POST /users HTTP/1.1" 400
USER-SERVICE - - [17/Aug/2018:09:23:55 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:26:51 +0000] "POST /users HTTP/1.1" 201
INVOICE-SERVICE - - [17/Aug/2018:09:26:53 +0000] "POST /invoices HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:29:11 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [17/Aug/2018:09:29:13 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [18/Aug/2018:09:30:54 +0000] "POST /users HTTP/1.1" 400
USER-SERVICE - - [18/Aug/2018:09:31:55 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [18/Aug/2018:09:31:56 +0000] "POST /users HTTP/1.1" 201
INVOICE-SERVICE - - [18/Aug/2018:10:26:53 +0000] "POST /invoices HTTP/1.1" 201
USER-SERVICE - - [18/Aug/2018:10:32:56 +0000] "POST /users HTTP/1.1" 201
USER-SERVICE - - [18/Aug/2018:10:33:59 +0000] "POST /users HTTP/1.1" 201
EOD;

        file_put_contents(sprintf('%s/var/%s', $kernel->getProjectDir(), 'logs-test.log'), $data);
    }
}
