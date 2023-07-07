<?php

declare(strict_types=1);

namespace App\Domain\Event\Entity;

use App\Infra\Doctrine\AppTypes;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\UuidInterface;

/** @final  */

#[Entity]
#[Table('events')]
readonly class Event
{
    public function __construct(
        #[Id]
        #[Column(type: AppTypes::UUID)]
        public UuidInterface $id,

        #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
        public \DateTimeImmutable $createAt,

        #[Column(type: Types::STRING)]
        public string $name,

        #[Column(type: Types::JSON)]
        public object $data,
    ) {
    }
}
