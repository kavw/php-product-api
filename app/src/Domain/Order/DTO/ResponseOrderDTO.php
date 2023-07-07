<?php

declare(strict_types=1);

namespace App\Domain\Order\DTO;

final readonly class ResponseOrderDTO
{
    public function __construct(
        public string $id,
        public string $status,
        public string $total,
        /**
         * @var ResponseOrderProductDTO[]
         */
        public array $products
    ) {
    }
}
