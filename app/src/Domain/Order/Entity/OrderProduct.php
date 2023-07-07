<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Domain\Exception\ValidationException;
use App\Infra\Doctrine\AppTypes;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

/** @final */

#[Entity]
#[Table('order_products')]
class OrderProduct
{
    public function __construct(
        #[Id]
        #[Column(type: AppTypes::UUID)]
        private UuidInterface $id,

        #[ManyToOne(targetEntity: Order::class, inversedBy: 'products')]
        #[JoinColumn(name: 'order_id', referencedColumnName: 'id', nullable: false)]
        private Order $order,

        #[Column(name: 'product_id', type: AppTypes::UUID)]
        private UuidInterface $productId,

        #[Column(type: Types::STRING)]
        private string $name,

        #[Column(type: AppTypes::MONEY)]
        private Money $price,

        #[Column(type: Types::INTEGER)]
        private int $count = 1,
    ) {
        if ($this->count < 1) {
            throw new ValidationException(
                "Product count must a positive value"
            );
        }
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getSum(): Money
    {
        return $this->price->multiply($this->count);
    }
}
