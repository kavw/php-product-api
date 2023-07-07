<?php

declare(strict_types=1);

namespace App\Infra\Secret;

use function App\env;

readonly class EnvSecretProvider implements SecretProviderInterface
{
    public function get(string $name): string
    {
        return env(
            $name,
            fn ($v) => $v !== null
                ? $v
                : throw new \RuntimeException(
                    "Can't get secret for {$name}"
                )
        );
    }
}
