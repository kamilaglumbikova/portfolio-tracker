<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Holding;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['AAPL', '10', '185.50', '2025-03-12'],
            ['MSFT', '5', '410.00', '2025-06-01'],
            ['NVDA', '20', '610.00', '2025-05-01'],
            ['KO', '4', '230.00', '2025-04-01'],
            ['VOO', '14', '210.00', '2025-05-10'],
        ];

        foreach ($data as [$symbol, $quantity, $buyPrice, $boughtAt]) {
            $holding = new Holding();
            $holding
                ->setSymbol($symbol)
                ->setQuantity($quantity)
                ->setBuyPrice($buyPrice)
                ->setBoughtAt(new \DateTimeImmutable($boughtAt));
            $manager->persist($holding);
        }

        $manager->flush();
    }
}
