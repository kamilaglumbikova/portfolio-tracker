<?php

declare(strict_types=1);

namespace App\Quote;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;


final class FinnhubQuoteProvider implements QuoteProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $finnhubClient,
    ) {}

    public function getPrice(string $symbol): string
    {
        try {
            $response = $this->finnhubClient->request('GET', 'quote', [
                'query' => ['symbol' => $symbol],
            ]);

            if ($response->getStatusCode() === 429) {
                throw new QuoteUnavailableException('Limit Finnhub API překročen.');
            }

            $data = $response->toArray();

            if (empty($data['c'])) {
                throw new QuoteUnavailableException("Neznámý symbol: '$symbol'");
            }

            return number_format((float) $data['c'], 4, '.', '');

        }  catch (ExceptionInterface $e) {
            throw new QuoteUnavailableException('Finnhub API je nedostupné.', previous: $e);
        }
        
    }
}