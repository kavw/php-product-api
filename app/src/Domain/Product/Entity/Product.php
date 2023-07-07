<?php

declare(strict_types=1);

namespace App\Domain\Product\Entity;

use App\Infra\Doctrine\AppTypes;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

/** @final */

#[Entity]
#[Table('products')]
class Product
{
    public function __construct(
        #[Id]
        #[Column(type: AppTypes::UUID)]
        private UuidInterface $id,

        #[Column(type: Types::STRING)]
        private string $name,

        #[Column(type: AppTypes::MONEY)]
        private Money $price,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}
