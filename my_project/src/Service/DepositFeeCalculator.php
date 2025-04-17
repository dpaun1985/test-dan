<?php
namespace App\Service;

use App\Model\Client;

class DepositFeeCalculator implements FeeCalculatorInterface
{
    private const DEPOSIT_FEE_PERCENTAGE = 0.03;

    /**
     * Calculate the deposit fee based on the amount.
     *
     * @param Client $client
     * @return float
     */
    public function calculateFee(Client $client): float
    {
        return $client->getAmount() * self::DEPOSIT_FEE_PERCENTAGE / 100;
    }
}