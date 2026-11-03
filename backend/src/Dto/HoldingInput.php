<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class HoldingInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex('/^[A-Z.]{1,10}$/', message: 'Symbol musí být velkými písmeny, např. AAPL.')]
        public readonly string $symbol,

        #[Assert\NotBlank]
        #[Assert\Type('numeric')]
        #[Assert\Positive]
        public readonly string $quantity,

        #[Assert\NotBlank]
        #[Assert\Type('numeric')]
        #[Assert\Positive]
        public readonly string $buyPrice,

        #[Assert\NotNull]
        #[Assert\LessThanOrEqual('today', message: 'Datum nákupu nemůže být v budoucnosti.')]
        public readonly \DateTimeImmutable $boughtAt,
    ) {}
}