<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Create;

final readonly class Request
{
    public function __construct(
        /**
         * @var array<array<string, string>>$products
         */
        public array $products = [],
    ) {
    }
}
