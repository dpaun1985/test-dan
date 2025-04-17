<?php
namespace App\Service;

class CurrencyExchangeService
{
    /**
     * The exchange rate cache for EUR to other currencies.
     *
     * @var array
     */
    private array $eurExchangeRate = [];

    /**
     * Class constructor.
     *
     * @param ExchangeApiService $exchangeApiService
     */
    public function __construct(private readonly ExchangeApiService $exchangeApiService)
    {
    }

    /**
     * @param string $currency
     * @return float
     */
    public function getRate(string $currency): float
    {
        // if the currency is EUR, we don't need to convert anything.
        if ($currency === 'EUR') {
            return 1;
        }

        // If the exchange rate for the currency is not already cached, fetch it from the API.
        if (!isset($this->eurExchangeRate[$currency])) {
            $rate = $this->exchangeApiService->fetchExchangeRate('EUR', $currency);
            $this->eurExchangeRate[$currency] = $rate;
        }

        return $this->eurExchangeRate[$currency];
    }

    /**
     * Convert an amount from EUR to another currency or vice versa.
     *
     * @param float $amount
     * @param string $from
     * @param string $to
     * @return float
     * @throws \Exception
     */
    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        if (!in_array('EUR', [$from, $to])) {
            throw new \Exception('Invalid currency conversion. Only EUR is supported.');
        }

        // If the amount is in EUR and we want to convert it to another currency.
        if ('EUR' === $from) {
            $exchangeRate = $this->getRate($to);
            return $amount * $exchangeRate;
        }

        // If the amount is in another currency and we want to convert it to EUR.
        if ('EUR' === $to) {
            $exchangeRate = $this->getRate($from);
            return $amount / $exchangeRate;

        }
    }

}