<?php

namespace App\Tests\Command;

use App\Command\ReadLogLineCommand;
use App\Service\ReadNextUnprocessedLineService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/***
 * @covers \App\Command\ReadLogLineCommand
 * @covers \App\Service\ReadNextUnprocessedLineService
 */
class ReadLogLineCommandTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->createTestLogFileIfDoesNotExist(self::$kernel);
    }

    public function testExecute(): void
    {
        $kernel = self::$kernel;

        $logFilePath = sprintf('%s/var/%s', $kernel->getProjectDir(), 'logs.log');
        $this->assertFileExists($logFilePath);

        $parameterBagMock = $this->mockParameterBag($kernel);
        $readNextUnprocessedLineService = new ReadNextUnprocessedLineService();

        $command = new ReadLogLineCommand($parameterBagMock, $readNextUnprocessedLineService);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertStringContainsString('reading a new line from log file command triggered', $commandTester->getDisplay());
        $this->assertStringContainsString('file found, Getting record ', $commandTester->getDisplay());

        $positionFilePath = sprintf('%s/var/%s', $kernel->getProjectDir(), 'position.txt');
        $this->assertFileExists($positionFilePath);
    }

    public function mockParameterBag(KernelInterface $kernel): MockObject
    {
        $parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $parameterBagMock->method('get')->willReturnMap([
            ['kernel.project_dir', $kernel->getProjectDir()],
            ['env(LOG_FILE_NAME)', 'logs.log'],
            ['env(POSITION_FILE_NAME)', 'position.txt'],
        ]);
        return $parameterBagMock;
    }

    private function createTestLogFileIfDoesNotExist(?\Symfony\Component\HttpKernel\KernelInterface $kernel): void
    {
        $logFilePath = sprintf('%s/var/%s', $kernel->getProjectDir(), 'logs.log');
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

        file_put_contents(sprintf('%s/var/%s', $kernel->getProjectDir(), 'logs.log'), $data);
    }
}
