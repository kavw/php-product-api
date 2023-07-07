<?php

declare(strict_types=1);

namespace App\Infra\Secret;

interface SecretProviderInterface
{
    public function get(string $name): string;
}
