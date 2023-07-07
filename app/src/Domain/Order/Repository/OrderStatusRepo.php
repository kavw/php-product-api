<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Entity\OrderStatus;
use App\Domain\Order\Entity\OrderStatusCode;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderStatusRepo
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function created(): OrderStatus
    {
        return $this->getByCode(OrderStatusCode::CREATED);
    }

    public function paid(): OrderStatus
    {
        return $this->getByCode(OrderStatusCode::PAID);
    }

    private function getByCode(OrderStatusCode $code): OrderStatus
    {
        return $this->entityManager
            ->getRepository(OrderStatus::class)
            ->findOneBy([
                'code' => $code->value
            ]) ?? throw new \LogicException("Null is unexpected here");
    }
}
