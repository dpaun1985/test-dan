<?php
namespace App\Service;

use App\Service\ClientValidator;
use App\Model\Client;
use App\Service\Validator\ActionValidator;
use App\Service\Validator\DateValidator;
use App\Service\Validator\NrFieldsValidator;
use App\Service\Validator\NumericValidator;
use App\Service\Validator\StringValidator;
use App\Service\Validator\TypeValidator;

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
        $this->clientValidator
            ->add(new NrFieldsValidator($clientData))
            ->add(new StringValidator([$clientData[2], $clientData[3], $clientData[5]]))
            ->add(new NumericValidator([$clientData[1], $clientData[4]]))
            ->add(new DateValidator($clientData[0]))
            ->add(new TypeValidator($clientData[2]))
            ->add(new ActionValidator($clientData[3]))
            ->validate();
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