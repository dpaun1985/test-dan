<?php
namespace App\Tests\Service;

use App\Service\CurrencyExchangeService;
use App\Service\DepositFeeCalculator;
use App\Service\ExchangeApiService;
use App\Service\WithdrawFeeCalculator;
use App\Service\ClientValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use \App\Service\FeeService;

class FeeServiceTest extends TestCase
{
    private FeeService $feeService;

    private ExchangeApiService|MockObject $exchangeApiService;

    protected function setUp(): void
    {
        $this->exchangeApiService = $this->createMock(ExchangeApiService::class);

        $exchangeRateService = new CurrencyExchangeService($this->exchangeApiService);
        $this->feeService = new FeeService(new DepositFeeCalculator(), new WithdrawFeeCalculator($exchangeRateService), new ClientValidator());


        parent::setUp();
    }
    public function testCalculatesDepositFeeCorrectly()
    {
        $clientData = ['2016-01-05', 4, 'private', 'deposit', 200.00, 'EUR'];


        $result = $this->feeService->calculateFee($clientData);

        $this->assertEquals('0.06', $result);
    }

    public function testCalculatesWithdrawFeeCorrectly()
    {
        $clientData = ['2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR'];

        $result = $this->feeService->calculateFee($clientData);

        $this->assertEquals('0.60', $result);
    }

    public function testThrowsExceptionForInvalidAction()
    {
        $clientData = ['2014-12-31', 4, 'private', 'invalid_action', 1200.00, 'EUR'];


        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The action type is not valid. It must be either "deposit" or "withdraw".');

        $this->feeService->calculateFee($clientData);
    }

    public function roundsFeeCorrectlyForJPY()
    {
        $clientData = ['2016-02-19', 4, 'private', 'withdraw', 3000000, 'JPY'];
        $this->exchangeApiService
            ->method('fetchExchangeRate')
            ->willReturn(1.1497);

        $result = $this->feeService->calculateFee($clientData);

        $this->assertSame('8612', $result);
    }
}