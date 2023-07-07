<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\Entity;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Repository\OrderStatusRepo;
use App\Infra\IdGenerator\IdGeneratorInterface;
use Psr\Clock\ClockInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class OrderFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
        private OrderStatusRepo $statusRepository,
        private ClockInterface $clock,
    ) {
    }

    public function create(UuidInterface $userId): Order
    {
        return new Order(
            $this->idGenerator->generate(),
            $userId,
            $this->statusRepository->created(),
            $this->clock->now(),
        );
    }
}
