<?php
declare(strict_types=1);

interface HasValue 
{
    public function value(): int;
    public function name(): string;
}

final class Position implements HasValue
{
    public function __construct(
        private readonly string $symbol,
        private readonly int $quantity,
        private readonly int $priceCents
    ) {}

    public function value(): int
    {
        return $this->quantity * $this->priceCents;
    }

    public function name(): string
    {
        return $this->symbol;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}

enum Currency
{
    case USD;
    case CZK;
}

final class Portfolio
{
    /** @var Position[] */
    private array $positions = [];

    public function __construct(
        private readonly Currency $currency
    ) {}

    public function add(Position $position): void
    {
        $this->positions[] = $position;
    }

    public function totalValue(): int
    {
        $total = 0;
        foreach ($this->positions as $position) {
            $total += $position->value();
        }
        return $total;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function names(): string
    {
        $names = '';
        foreach ($this->positions as $position) {
            $names .= ", " . $position->name();
        }
        return $names;
    }
}

$portfolio = new Portfolio(Currency::USD);

$portfolio->add(new Position('AAPL', 10, 15000)); // 10 * 150 = 1500 USD
$portfolio->add(new Position('MSFT', 5, 30000));  // 5 * 300 = 1500 USD
$portfolio->add(new Position('TSLA', 2, 22550));  // 2 * 225.50 = 451 USD

$totalCents = $portfolio->totalValue();

$totalDollars = $totalCents / 100;

echo "Měna portfolia: " . $portfolio->getCurrency()->name . "\n";
echo "Celková hodnota: $" . number_format($totalDollars, 2, '.', ' ') . "\n";
echo "Nazvy akcii: " . $portfolio->names() . "\n";