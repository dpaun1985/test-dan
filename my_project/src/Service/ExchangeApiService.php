<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeApiService
{
    private const URL = 'https://api.apilayer.com/exchangerates_data/latest';

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /**
     * Fetch the exchange rate for a given base currency and target currency.
     *
     * @param string $base The base currency (e.g., 'EUR').
     * @param string $currency The target currency (e.g., 'USD').
     * @return float The exchange rate.
     */
    public function fetchExchangeRate( string $base, string $currency): float
    {
        $response = $this->client->request('GET', self::URL, [
            'query' => [
                'base' => $base,
                'symbols' => $currency,
            ],
            'headers' => [
                'apikey' => $_ENV['EXCHANGE_API_KEY'],
            ],
        ]);

        return $response->toArray()["rates"][$currency];
    }
}