<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Entity\Order;
use App\Domain\Order\Entity\OrderPayment;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class OrderPaymentRepo
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function findById(UuidInterface $id, bool $lock = false): ?OrderPayment
    {
        return $this->entityManager
            ->getRepository(OrderPayment::class)
            ->find($id, $lock ? LockMode::PESSIMISTIC_WRITE : null);
    }
}
