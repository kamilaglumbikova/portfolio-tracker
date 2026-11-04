<?php

declare(strict_types=1);

namespace App\Portfolio;

use App\Entity\Holding;
use App\Quote\QuoteProviderInterface;

final class PortfolioCalculator
{
    private const SCALE = 4;

    public function __construct(
        private readonly QuoteProviderInterface $quotes,
    ) {}

    /**
     * @param Holding[] $holdings
     */
    public function calculate(array $holdings): PortfolioValuation
    {
        $totalValue = '0.0000';
        $totalCost = '0.0000';
        $rows = [];

        // 1. průchod: ceny, hodnoty a součty
        foreach ($holdings as $holding) {
            $price = $this->quotes->getPrice($holding->getSymbol());
            $value = bcmul($holding->getQuantity(), $price, self::SCALE);

            $cost = bcmul($holding->getQuantity(), $holding->getBuyPrice(), self::SCALE);

            $totalValue = bcadd($totalValue, $value, self::SCALE);
            $totalCost = bcadd($totalCost, $cost, self::SCALE);

            $rows[] = [$holding, $price, $value, $cost];
        }

        // 2. průchod: podíl na portfoliu potřebuje znát celkovou hodnotu
        $positions = [];
        foreach ($rows as [$holding, $price, $value, $cost]) {

            $gain = bcsub($value, $cost, self::SCALE);

            $positions[] = new PositionValuation(
                symbol: $holding->getSymbol(),
                quantity: $holding->getQuantity(),
                buyPrice: $holding->getBuyPrice(),
                currentPrice: $price,
                marketValue: $value,
                costBasis: $cost,
                gain: $gain,
                gainPercent: $this->percent($gain, $cost),
                weightPercent: $this->percent($value, $totalValue),
            );
        }

        $totalGain = bcsub($totalValue, $totalCost, self::SCALE);

        return new PortfolioValuation(
            positions: $positions,
            totalValue: $totalValue,
            totalCost: $totalCost,
            totalGain: $totalGain,
            totalGainPercent: $this->percent($totalGain, $totalCost),
        );
    }

    /**
     * Kolik procent je $part z $whole, zaokrouhleno na 2 místa.
     */
    private function percent(string $part, string $whole): string
    {
        if (bccomp($whole, '0', self::SCALE) === 0) {
            return '0.00';  // dělení nulou
        }

        $ratio = bcdiv(bcmul($part, '100', self::SCALE + 2), $whole, self::SCALE + 2);

        return number_format((float) $ratio, 2, '.', '');
    }
}