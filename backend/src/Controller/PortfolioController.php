<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\HoldingRepository;
use App\Portfolio\PortfolioCalculator;
use App\Quote\QuoteUnavailableException;
use Symfony\Component\HttpFoundation\Response;

final class PortfolioController extends AbstractController
{
    #[Route('/api/portfolio', name: 'api_portfolio_show', methods: ['GET'], format: 'json')]
    public function show(HoldingRepository $repository, PortfolioCalculator $calculator): JsonResponse
    {
        $holdings = $repository->findAll();

        try {
            $valuation = $calculator->calculate($holdings);
        } catch (QuoteUnavailableException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        }

        return $this->json($valuation);
    }
}
