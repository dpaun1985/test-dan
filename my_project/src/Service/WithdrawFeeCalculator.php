<?php
namespace App\Service;

use App\Model\Client;

class WithdrawFeeCalculator
{
    private const BUSINESS_FEE_PERCENTAGE = 0.5;
    private const PRIVATE_FEE_PERCENTAGE = 0.3;

    private const CLIENT_BUSINESS = 'business';
    private const CLIENT_PRIVATE = 'private';

    /**
     * The amount withdrawn by the client in the last week.
     * The key is the clientId and the value is an array with the week number as key and the amount and withdraws count as value.
     * [
     *   'clientId' => [
     *       '2023-42' => [
     *           'amount' => 1000,
     *           'count' => 2,
     *       ],
     *   ],
     * ]
     */
    private $privateAmountWeeks = [];


    public function __construct(private readonly CurrencyExchangeService $currencyExchangeService)
    {
    }

    /**
     * Calculate the withdraw fee based on the client type and amount.
     *
     * @param Client $client
     * @return float
     */
    public function calculateFee(Client $client): float
    {
        if (self::CLIENT_BUSINESS === $client->getClientType()) {
            return $this->calculateWithdrawClientBusiness($client->getAmount());
        }

        if (self::CLIENT_PRIVATE === $client->getClientType()) {
            return $this->calculateWithdrawClientPrivate($client);
        }

        return 0;
    }

    /**
     * Calculate the withdraw fee for a business client.
     *
     * @param float $amount
     * @return float
     */
    private function calculateWithdrawClientPrivate(Client $client): float
    {
        $amountEur =  $this->currencyExchangeService->convert($client->getAmount(), $client->getCurrency(), 'EUR');
        $clientId = $client->getClientId();
        $clientDate = $client->getDate();

        // Get ISO week identifier: 'Year-WeekNumber'
        $weekIdentifier = $clientDate->format("o-W");

        $hadPreviousWithdraw = isset($this->privateAmountWeeks[$clientId][$weekIdentifier]);
        $previousAmount = !$hadPreviousWithdraw ? 0 : $this->privateAmountWeeks[$clientId][$weekIdentifier]['amount'];
        $nrPreviousWithdraw = !$hadPreviousWithdraw ? 0 : $this->privateAmountWeeks[$clientId][$weekIdentifier]['count'];

        $amountForFee = $this->getAmountForFee($previousAmount, $amountEur, $nrPreviousWithdraw);
        $this->privateAmountWeeks[$clientId][$weekIdentifier] = [
            'amount' => $previousAmount + $amountEur,
            'count' => $nrPreviousWithdraw++,
        ];
        if (0 === $amountForFee) {
            return 0;
        }

        $fee = $amountForFee * self::PRIVATE_FEE_PERCENTAGE / 100;

        return $this->currencyExchangeService->convert($fee, 'EUR', $client->getCurrency());
    }

    /**
     * Calculate the amount for the fee based on the previous amount and the current amount.
     *
     * @param float $previousAmount
     * @param float $amountEur
     * @param int $nrPreviousWithdraw
     * @return float
     */
    private function getAmountForFee(float $previousAmount, float $amountEur, int $nrPreviousWithdraw): float
    {
        // 1000.00 EUR for a week (from Monday to Sunday) is free of charge. Only for the first 3 withdraw operations per a week.
        if ($previousAmount + $amountEur <= 1000 && $nrPreviousWithdraw < 3) {
            return 0;
        }

        // The first 1000 EUR are free of charge.
        if ($previousAmount < 1000 && $nrPreviousWithdraw < 3) {
            return $previousAmount + $amountEur - 1000;
        }

        return $amountEur;
    }

    /**
     * Calculate the withdraw fee for a business client.
     *
     * @param float $amount
     * @return float
     */
    private function calculateWithdrawClientBusiness(float $amount): float
    {
        return $amount * self::BUSINESS_FEE_PERCENTAGE / 100;
    }
}