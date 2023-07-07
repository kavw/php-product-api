<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Entity\Order;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Cache\Lock;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderRepo
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findById(string $id, bool $lock = false): ?Order
    {
        return $this->entityManager
            ->getRepository(Order::class)
            ->find($id, $lock ? LockMode::PESSIMISTIC_WRITE : null);
    }
}
