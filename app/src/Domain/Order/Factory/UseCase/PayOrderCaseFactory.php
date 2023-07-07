<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\UseCase;

use App\Domain\EventDispatcherInterface;
use App\Domain\Order\Repository\OrderPaymentStatusRepo;
use App\Domain\Order\Repository\OrderStatusRepo;
use App\Domain\Order\Service\PaymentGatewayClientInterface;
use App\Domain\Order\UseCase\PayOrder\PayOrderCase;
use Psr\Clock\ClockInterface;

final readonly class PayOrderCaseFactory
{
    public function __construct(
        private OrderPaymentStatusRepo $paymentStatusRepository,
        private OrderStatusRepo $orderStatusRepository,
        private ClockInterface $clock,
        private PaymentGatewayClientInterface $paymentGateway,
    ) {
    }

    public function create(
        EventDispatcherInterface $eventDispatcher
    ): PayOrderCase {
        return new PayOrderCase(
            $this->paymentStatusRepository,
            $this->orderStatusRepository,
            $this->clock,
            $this->paymentGateway,
            $eventDispatcher
        );
    }
}
