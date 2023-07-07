<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final class ClockService implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
