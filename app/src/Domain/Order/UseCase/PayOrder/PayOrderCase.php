<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCase\PayOrder;

use App\Domain\EventDispatcherInterface;
use App\Domain\Order\Entity\OrderPayment;
use App\Domain\Order\Repository\OrderPaymentStatusRepo;
use App\Domain\Order\Repository\OrderStatusRepo;
use App\Domain\Order\Service\PaymentGatewayClientInterface;
use App\Infra\Exception\InternalException;
use Psr\Clock\ClockInterface;

final readonly class PayOrderCase
{
    public function __construct(
        private OrderPaymentStatusRepo $paymentStatusRepository,
        private OrderStatusRepo $orderStatusRepository,
        private ClockInterface $clock,
        private PaymentGatewayClientInterface $paymentGateway,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function process(OrderPayment $payment): OrderPayment
    {
        $res = $this->paymentGateway
            ->createPayment($payment->getId()->toString());

        if (!$res) {
            throw new InternalException(
                "An error has occurred, please try again later"
            );
        }

        $order = $payment->getOrder();
        $order->pay(
            $this->orderStatusRepository,
            $this->clock,
            $this->paymentStatusRepository,
        );

        $this->eventDispatcher->raise(
            new OrderPaidEvent($order->getId())
        );

        return $payment;
    }
}
