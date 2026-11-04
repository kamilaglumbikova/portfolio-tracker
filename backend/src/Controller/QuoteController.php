<?php

declare(strict_types=1);

namespace App\Controller;

use App\Quote\QuoteProviderInterface;
use App\Quote\QuoteUnavailableException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuoteController extends AbstractController
{
    #[Route('/api/quotes/{symbol}', name: 'api_quotes_show', methods: ['GET'], format: 'json')]
    public function show(string $symbol, QuoteProviderInterface $provider): JsonResponse
    {
        $symbol = strtoupper($symbol);

        try {
            $price = $provider->getPrice($symbol);
        } catch (QuoteUnavailableException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }

        return $this->json([
            'symbol' => $symbol,
            'price' => $price,
        ]);
    }
}
