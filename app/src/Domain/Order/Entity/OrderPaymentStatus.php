<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/** @final */

#[Entity]
#[Table('order_payment_statuses')]
class OrderPaymentStatus
{
    public function __construct(
        #[Id]
        #[Column(type: Types::INTEGER)]
        private int $id,

        #[Column(type: Types::STRING, enumType: OrderPaymentStatusCode::class)]
        private OrderPaymentStatusCode $code,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): OrderPaymentStatusCode
    {
        return $this->code;
    }
}
