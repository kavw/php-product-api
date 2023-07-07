<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Pay;

final readonly class Request
{
    public function __construct(
        public string $sum
    ) {
    }
}
