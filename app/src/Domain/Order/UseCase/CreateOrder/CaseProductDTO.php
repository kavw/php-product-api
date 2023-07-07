<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCase\CreateOrder;

use App\Domain\Exception\ValidationException;
use App\Domain\Product\Entity\Product;

final readonly class CaseProductDTO
{
    public function __construct(
        public Product $product,
        public int $count,
    ) {
        if ($this->count < 1) {
            throw new ValidationException(
                "The product count parameter should be a positive"
            );
        }
    }
}
