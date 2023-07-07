<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\Entity;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Entity\OrderPayment;
use App\Domain\Order\Repository\OrderPaymentStatusRepo;
use App\Infra\IdGenerator\IdGeneratorInterface;
use Money\Money;
use Psr\Clock\ClockInterface;

final readonly class OrderPaymentFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
        private ClockInterface $clock,
        private OrderPaymentStatusRepo $statusRepository,
    ) {
    }

    public function create(Order $order, Money $sum): OrderPayment
    {
        return new OrderPayment(
            $this->idGenerator->generate(),
            $order,
            $this->statusRepository->created(),
            $sum,
            $this->clock->now(),
        );
    }
}
