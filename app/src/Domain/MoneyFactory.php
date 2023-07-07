<?php

declare(strict_types=1);

namespace App\Domain;

use App\Defaults;
use Money\Currency;
use Money\Money;

final class MoneyFactory
{
    public function create(int|string $amount): Money
    {
        return new Money(
            $amount,
            new Currency(Defaults::CURRENCY)
        );
    }
}
