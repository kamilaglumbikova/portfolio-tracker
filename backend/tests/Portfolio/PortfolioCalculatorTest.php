<?php

declare(strict_types=1);

namespace App\Tests\Portfolio;

use App\Entity\Holding;
use App\Portfolio\PortfolioCalculator;
use App\Quote\QuoteProviderInterface;
use PHPUnit\Framework\TestCase;

final class PortfolioCalculatorTest extends TestCase
{
    public function testCalculatesValueGainAndWeight(): void
    {
        // Falešný provider: pevné ceny, žádné volání Finnhubu
        $quotes = new class implements QuoteProviderInterface {
            public function getPrice(string $symbol): string
            {
                return ['AAPL' => '200.0000', 'MSFT' => '270.0000'][$symbol];
            }
        };

        $calculator = new PortfolioCalculator($quotes);

        $result = $calculator->calculate([
            $this->holding('AAPL', '10', '150.0000'),  // koupeno za 1500, teď 2000
            $this->holding('MSFT', '5', '300.0000'),   // koupeno za 1500, teď 1350
        ]);

        // AAPL: zisk
        $aapl = $result->positions[0];
        self::assertSame('2000.0000', $aapl->marketValue);
        self::assertSame('1500.0000', $aapl->costBasis);
        self::assertSame('500.0000', $aapl->gain);
        self::assertSame('33.33', $aapl->gainPercent);
        self::assertSame('59.70', $aapl->weightPercent);

        // MSFT: ztráta
        $msft = $result->positions[1];
        self::assertSame('1350.0000', $msft->marketValue);
        self::assertSame('-150.0000', $msft->gain);
        self::assertSame('-10.00', $msft->gainPercent);
        self::assertSame('40.30', $msft->weightPercent);

        // Celé portfolio
        self::assertSame('3350.0000', $result->totalValue);
        self::assertSame('3000.0000', $result->totalCost);
        self::assertSame('350.0000', $result->totalGain);
        self::assertSame('11.67', $result->totalGainPercent);
    }

    public function testEmptyPortfolioIsZero(): void
    {
        $quotes = new class implements QuoteProviderInterface {
            public function getPrice(string $symbol): string
            {
                throw new \LogicException('Prázdné portfolio nemá volat API.');
            }
        };

        $result = (new PortfolioCalculator($quotes))->calculate([]);

        self::assertSame([], $result->positions);
        self::assertSame('0.0000', $result->totalValue);
        self::assertSame('0.00', $result->totalGainPercent);
    }

    private function holding(string $symbol, string $quantity, string $buyPrice): Holding
    {
        return (new Holding())
            ->setSymbol($symbol)
            ->setQuantity($quantity)
            ->setBuyPrice($buyPrice)
            ->setBoughtAt(new \DateTimeImmutable('2025-01-01'));
    }
}