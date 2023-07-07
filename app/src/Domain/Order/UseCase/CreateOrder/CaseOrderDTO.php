<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCase\CreateOrder;

use App\Domain\Exception\ValidationException;
use App\Domain\Product\Entity\Product;
use Ramsey\Uuid\UuidInterface;

final readonly class CaseOrderDTO
{
    public function __construct(
        public UuidInterface $uuid,
        /** @var CaseProductDTO[] */
        public array $products,
    ) {
        if (!$this->products) {
            throw new ValidationException(
                "It needs at least one product to create an order"
            );
        }
    }
}
