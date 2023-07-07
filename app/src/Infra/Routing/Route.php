<?php

declare(strict_types=1);

namespace App\Infra\Routing;

final readonly class Route
{
    public function __construct(
        public string $name,
        public Method $method,
        public string $path,
        public ?string $actionRequestClass = null,
        /** @var list<string> */
        public array $arguments = [],
    ) {
    }
}
