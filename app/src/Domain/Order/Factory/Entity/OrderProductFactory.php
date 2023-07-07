<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\Entity;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Entity\OrderProduct;
use App\Domain\Product\Entity\Product;
use App\Infra\IdGenerator\IdGeneratorInterface;

final readonly class OrderProductFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function create(
        Order $order,
        Product $product,
        int $count = 1,
    ): OrderProduct {
        return new OrderProduct(
            id: $this->idGenerator->generate(),
            order: $order,
            productId: $product->getId(),
            name: $product->getName(),
            price: $product->getPrice(),
            count: $count
        );
    }
}
