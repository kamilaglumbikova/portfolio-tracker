<?php

declare(strict_types=1);

namespace App\Portfolio;

final class PortfolioValuation
{
    /**
     * @param list<PositionValuation> $positions
     */
    public function __construct(
        public readonly array $positions,
        public readonly string $totalValue,
        public readonly string $totalCost,
        public readonly string $totalGain,
        public readonly string $totalGainPercent,
    ) {}
}