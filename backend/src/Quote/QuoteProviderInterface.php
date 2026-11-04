<?php

declare(strict_types=1);

namespace App\Quote;

interface QuoteProviderInterface
{
    /**
     * Vrátí aktuální cenu akcie v USD jako přesné číslo v textu, např. "270.3700".
     *
     * @throws QuoteUnavailableException když cenu nejde zjistit
     */
    public function getPrice(string $symbol): string;
}