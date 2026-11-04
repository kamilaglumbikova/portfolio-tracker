<?php

declare(strict_types=1);

namespace App\Quote;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsAlias(QuoteProviderInterface::class)]
final class CachedQuoteProvider implements QuoteProviderInterface
{
    private const TTL_SECONDS = 60;

    public function __construct(
        private readonly FinnhubQuoteProvider $inner,
        private readonly CacheInterface $cache,
    ) {}

    public function getPrice(string $symbol): string
    {
        return $this->cache->get(
            "quote_$symbol",
            function (ItemInterface $item) use ($symbol): string {
                $item->expiresAfter(self::TTL_SECONDS);

                return $this->inner->getPrice($symbol);
            }
        );
    }
}