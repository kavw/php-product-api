<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\DTO;

use App\Domain\Order\DTO\ResponseOrderDTO;
use App\Domain\Order\Entity\Order;

final readonly class OrderFromEntityFactory
{
    public function __construct(
        private ProductFromEntityFactory $productFactory,
    ) {
    }

    public function create(Order $order): ResponseOrderDTO
    {
        return new ResponseOrderDTO(
            id: $order->getId()->toString(),
            status: $order->getStatus()->getCode()->value,
            total: $order->getSum()->getAmount(),
            products: $this->productFactory->createAll($order->getProducts()),
        );
    }
}
