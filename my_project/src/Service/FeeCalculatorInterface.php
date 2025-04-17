<?php
namespace App\Service;

use App\Model\Client;

interface FeeCalculatorInterface
{
    /**
     * Calculate the fee based on the amount.
     *
     * @param Client $client
     * @return float
     */
    public function calculateFee(Client $client): float;
}