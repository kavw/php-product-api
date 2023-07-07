<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\DTO;

use App\Domain\Order\DTO\ResponseOrderPaymentDTO;
use App\Domain\Order\Entity\OrderPayment;

final readonly class OrderPaymentFromEntityFactory
{
    public function __construct(
        private OrderFromEntityFactory $orderFromEntityFactory
    ) {
    }

    public function create(OrderPayment $payment): ResponseOrderPaymentDTO
    {
        return new ResponseOrderPaymentDTO(
            id: $payment->getId()->toString(),
            sum: $payment->getSum()->getAmount(),
            status: $payment->getStatus()->getCode()->value,
            order: $this->orderFromEntityFactory->create($payment->getOrder())
        );
    }
}
