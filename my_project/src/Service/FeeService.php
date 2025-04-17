<?php
namespace App\Service;

use App\Validator\ClientValidator;
use App\Model\Client;

class FeeService
{
    private const OPERATION_DEPOSIT = 'deposit';
    private const OPERATION_WITHDRAW = 'withdraw';

    public function __construct(
        private readonly DepositFeeCalculator  $depositFeeCalculator,
        private readonly WithdrawFeeCalculator $withdrawFeeCalculator,
        private readonly ClientValidator $clientValidator
    ) {
    }

    /**
     * Calculate the fee based on the client data.
     *
     * @param array $clientData
     * @return float
     * @throws \Exception
     */
    public function calculateFee(array $clientData): string
    {
        // Validate the client data.
        $this->clientValidator->validate($clientData);
        $client = new Client( $clientData );

        if ( self::OPERATION_DEPOSIT === $client->getAction() ) {
            $fee = $this->depositFeeCalculator->calculateFee($client);
        }
        if ( self::OPERATION_WITHDRAW === $client->getAction() ) {
            $fee = $this->withdrawFeeCalculator->calculateFee($client);
        }

        return $this->roundValueBasedOnCurrency($fee, $client->getCurrency());
    }

    /**
     * Round the value based on the currency.
     *
     * @param float $amount
     * @param string $currency
     * @return float
     */
    private function roundValueBasedOnCurrency($amount, $currency): string
    {
        // For JPY, round up to the nearest whole number.
        if ('JPY' === $currency) {
            return (string)ceil($amount);
        }

        return number_format((float)ceil($amount * 100) / 100, 2, '.', '');
    }
}