<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCase\CreateOrder;

use Ramsey\Uuid\UuidInterface;

final readonly class OrderCreatedEvent
{
    public function __construct(
        public UuidInterface $orderId,
    ) {
    }
}
