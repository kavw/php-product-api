<?php

declare(strict_types=1);

namespace App\Domain\Order\DTO;

final readonly class ResponseOrderProductDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $price,
        public int $count,
    ) {
    }
}
