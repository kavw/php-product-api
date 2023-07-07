<?php

declare(strict_types=1);

namespace App\Infra\Routing;

final readonly class DispatchResult
{
    public function __construct(
        public ActionInterface $action,
        /** @var list<mixed> */
        public array $arguments,
    ) {
    }
}
