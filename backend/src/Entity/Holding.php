<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HoldingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: HoldingRepository::class)]
class Holding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['holding:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Groups(['holding:read'])]
    private ?string $symbol = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 6)]
    #[Groups(['holding:read'])]
    private ?string $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 18, scale: 4)]
    #[Groups(['holding:read'])]
    private ?string $buyPrice = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['holding:read'])]
    private ?\DateTimeImmutable $boughtAt = null;

    #[ORM\Column]
    #[Groups(['holding:read'])]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getBuyPrice(): ?string
    {
        return $this->buyPrice;
    }

    public function setBuyPrice(string $buyPrice): static
    {
        $this->buyPrice = $buyPrice;

        return $this;
    }

    public function getBoughtAt(): ?\DateTimeImmutable
    {
        return $this->boughtAt;
    }

    public function setBoughtAt(\DateTimeImmutable $boughtAt): static
    {
        $this->boughtAt = $boughtAt;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
