<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\UseCase;

use App\Domain\EventDispatcherInterface;
use App\Domain\Order\Factory\Entity\OrderProductFactory;
use App\Domain\Order\Factory\Entity\OrderFactory;
use App\Domain\Order\UseCase\CreateOrder\CreateOrderCase;

final readonly class CreateOrderCaseFactory
{
    public function __construct(
        private OrderFactory $orderFactory,
        private OrderProductFactory $orderedProductFactory,
    ) {
    }

    public function create(EventDispatcherInterface $eventDispatcher): CreateOrderCase
    {
        return new CreateOrderCase(
            $this->orderFactory,
            $this->orderedProductFactory,
            $eventDispatcher,
        );
    }
}
