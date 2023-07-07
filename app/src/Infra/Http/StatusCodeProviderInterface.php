<?php

declare(strict_types=1);

namespace App\Infra\Http;

interface StatusCodeProviderInterface
{
    public function getStatusCode(): int;
}
