<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCase\CreateOrder;

use App\Domain\EventDispatcherInterface;
use App\Domain\Order\Entity\Order;
use App\Domain\Order\Factory\Entity\OrderProductFactory;
use App\Domain\Order\Factory\Entity\OrderFactory;

final readonly class CreateOrderCase
{
    public function __construct(
        private OrderFactory $orderFactory,
        private OrderProductFactory $orderedProductFactory,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function process(CaseOrderDTO $dto): Order
    {
        $order = $this->orderFactory->create($dto->uuid);
        foreach ($dto->products as $productDTO) {
            $orderedProduct = $this->orderedProductFactory->create(
                $order,
                $productDTO->product,
                $productDTO->count,
            );
            $order->addProduct($orderedProduct);
        }
        $this->eventDispatcher->raise(new OrderCreatedEvent($order->getId()));
        return $order;
    }
}
