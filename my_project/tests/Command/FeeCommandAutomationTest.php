<?php
namespace App\Tests\Command;

use App\Service\CurrencyExchangeService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use App\Command\FeeCommand;
use App\Service\FeeService;
use Symfony\Component\Console\Command\Command;
use \App\Service\DepositFeeCalculator;
use \App\Service\WithdrawFeeCalculator;
use \App\Validator\ClientValidator;
use \App\Service\ExchangeApiService;
use \PHPUnit\Framework\MockObject\MockObject;

class FeeCommandAutomationTest extends TestCase
{
    private FeeService $feeService;
    private FeeCommand $feeCommand;

    private ExchangeApiService|MockObject $exchangeApiService;

    protected function setUp(): void
    {
        $this->exchangeApiService = $this->createMock(ExchangeApiService::class);
        $this->exchangeApiService
            ->method('fetchExchangeRate')
            ->willReturnOnConsecutiveCalls(129.53, 1.1497);

        $exchangeRateService = new CurrencyExchangeService($this->exchangeApiService);
        $this->feeService = new FeeService(new DepositFeeCalculator(), new WithdrawFeeCalculator($exchangeRateService), new ClientValidator());
        $this->feeCommand = new FeeCommand($this->feeService);
        parent::setUp();
    }

    public function testProcessesInputFileAndChecksOutput()
    {
        $inputFile = __DIR__ . '/test_input.csv';
        $output = new BufferedOutput();

        // Create a temporary CSV file for testing
        file_put_contents($inputFile, "2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.00,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY");

        $input = new ArrayInput(['file' => $inputFile]);
        $exitCode = $this->feeCommand->run($input, $output);

        // Clean up the temporary file
        unlink($inputFile);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $output = $output->fetch();
        $this->assertStringContainsString("0.00
0.06
1.50
0
0.70
0.30
0.30
3.00
0.00
0.00
8612", $output);
    }
}