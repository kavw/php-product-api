<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Domain\Exception\DomainException;
use App\Domain\Order\Repository\OrderPaymentStatusRepo;
use App\Infra\Doctrine\AppTypes;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Money\Money;
use Psr\Clock\ClockInterface;
use Ramsey\Uuid\UuidInterface;

/** @final */

#[Entity]
#[Table('order_payments')]
class OrderPayment
{
    public function __construct(
        #[Id]
        #[Column(type: AppTypes::UUID)]
        private UuidInterface $id,

        #[OneToOne(inversedBy: 'payment', targetEntity: Order::class)]
        #[JoinColumn(name: 'order_id', referencedColumnName: 'id', unique: true, nullable: false)]
        private Order $order,

        #[ManyToOne(targetEntity: OrderPaymentStatus::class, fetch: 'EAGER')]
        #[JoinColumn(referencedColumnName: 'id', nullable: false, fieldName: 'status_id')]
        private OrderPaymentStatus $status,

        #[Column(type: AppTypes::MONEY)]
        private Money $sum,

        #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
        private \DateTimeImmutable $createdAt,

        #[Column(name: 'paid_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
        private ?\DateTimeImmutable $doneAt = null,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getStatus(): OrderPaymentStatus
    {
        return $this->status;
    }

    public function markAsDone(
        OrderPaymentStatusRepo $repo,
        ClockInterface $clock
    ): void {

        if ($this->status->getCode() !== OrderPaymentStatusCode::CREATED) {
            throw new DomainException(
                "The payment '{$this->id->toString()}' has wrong status to be done"
            );
        }

        $this->status = $repo->done();
        $this->doneAt = $clock->now();
    }

    public function getSum(): Money
    {
        return $this->sum;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDoneAt(): ?\DateTimeImmutable
    {
        return $this->doneAt;
    }
}
