<?php

declare(strict_types=1);

namespace App\Portfolio;

final class PositionValuation
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $quantity,
        public readonly string $buyPrice,
        public readonly string $currentPrice,
        public readonly string $marketValue,   // kusy × aktuální cena
        public readonly string $costBasis,     // kusy × nákupní cena
        public readonly string $gain,          // hodnota − náklady
        public readonly string $gainPercent,   // zisk v % z nákladů
        public readonly string $weightPercent, // podíl na celém portfoliu v %
    ) {}
}