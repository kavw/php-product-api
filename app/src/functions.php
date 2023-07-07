<?php

declare(strict_types=1);

namespace App;

/**
 * @template T
 * @param  string $name
 * @param  callable(string|null): T $cast
 * @return T
 */
function env(string $name, callable $cast): mixed
{
    $name = trim($name);
    if (!$name) {
        throw new \InvalidArgumentException("Name must be non-empty");
    }

    $val = getenv($name);
    if ($val === false) {
        return $cast(null);
    }

    if (!is_string($val)) {
        throw new \RuntimeException(
            "Env variable '$name' is expected to be a sting"
        );
    }

    $val = trim($val);
    return $cast($val ?: null);
}
