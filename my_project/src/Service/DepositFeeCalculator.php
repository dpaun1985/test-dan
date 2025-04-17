<?php
namespace App\Service;

class DepositFeeCalculator
{
    private const DEPOSIT_FEE_PERCENTAGE = 0.03;

    /**
     * Calculate the deposit fee based on the amount.
     *
     * @param float $amount
     * @return float
     */
    public function calculateFee(float $amount): float
    {
        return $amount * self::DEPOSIT_FEE_PERCENTAGE / 100;
    }
}