<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ServerRequestInterface;

interface AppInterface
{
    public static function boot(
        string $cacheDir = null,
        bool $isDebug = true,
    ): static;
}
