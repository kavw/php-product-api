<?php

declare(strict_types=1);

namespace App\Domain\Order\Repository;

use App\Domain\Order\Entity\OrderPaymentStatus;
use App\Domain\Order\Entity\OrderPaymentStatusCode;
use App\Domain\Order\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderPaymentStatusRepo
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function created(): OrderPaymentStatus
    {
        return $this->getByCode(OrderPaymentStatusCode::CREATED);
    }

    public function done(): OrderPaymentStatus
    {
        return $this->getByCode(OrderPaymentStatusCode::DONE);
    }

    private function getByCode(
        OrderPaymentStatusCode $code
    ): OrderPaymentStatus {
        return $this->em
            ->getRepository(OrderPaymentStatus::class)
            ->findOneBy([
                'code' => $code->value
            ]) ?? throw new \LogicException('Null is unexpected here');
    }
}
