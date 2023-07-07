<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCase\PayOrder;

use Ramsey\Uuid\UuidInterface;

final readonly class OrderPaidEvent
{
    public function __construct(
        public UuidInterface $orderId,
    ) {
    }
}
