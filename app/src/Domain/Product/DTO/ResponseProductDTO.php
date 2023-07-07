<?php

declare(strict_types=1);

namespace App\Domain\Product\DTO;

final readonly class ResponseProductDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $price,
    ) {
    }
}
