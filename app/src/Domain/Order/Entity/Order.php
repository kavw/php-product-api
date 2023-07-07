<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

use App\Domain\Exception\DomainException;
use App\Domain\Order\Repository\OrderPaymentStatusRepo;
use App\Domain\Order\Repository\OrderStatusRepo;
use App\Infra\Doctrine\AppTypes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Money\Money;
use Psr\Clock\ClockInterface;
use Ramsey\Uuid\UuidInterface;

/** @final */

#[Entity]
#[Table('orders')]
class Order
{
    /** @var Collection<int, OrderProduct> */
    #[OneToMany(mappedBy: 'order', targetEntity: OrderProduct::class)]
    private Collection $products;

    #[OneToOne(mappedBy: 'order', targetEntity: OrderPayment::class)]
    private ?OrderPayment $payment = null;

    public function __construct(
        #[Id]
        #[Column(type: AppTypes::UUID)]
        private UuidInterface $id,

        #[Column(name: 'user_id', type: AppTypes::UUID)]
        private UuidInterface $userId,

        #[ManyToOne(targetEntity: OrderStatus::class, fetch: 'EAGER')]
        #[JoinColumn(referencedColumnName: 'id', nullable: false, fieldName: 'status_id')]
        private OrderStatus $status,

        #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
        private \DateTimeImmutable $createdAt,

        #[Column(name: 'paid_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
        private ?\DateTimeImmutable $paidAt = null,
    ) {
        $this->products = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function isCreated(): bool
    {
        return $this->status->getCode() === OrderStatusCode::CREATED;
    }

    public function isPaid(): bool
    {
        return $this->status->getCode() === OrderStatusCode::PAID;
    }

    public function addProduct(
        OrderProduct $product
    ): void {
        $this->products->add($product);
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function pay(
        OrderStatusRepo $repo,
        ClockInterface $clock,
        OrderPaymentStatusRepo $paymentStatusRepo
    ): void {

        if (!$this->isCreated()) {
            throw new DomainException(
                "The order '{$this->id->toString()}' has wrong status to be paid"
            );
        }

        $this->status = $repo->paid();
        $this->paidAt = $clock->now();

        if (!$this->payment) {
            throw new DomainException(
                "The order {$this->id->toString()} doesn't have a payment"
            );
        }

        if (!$this->payment->getSum()->equals($this->getSum())) {
            throw new DomainException(
                "Payment sum mismatch for the order {$this->id->toString()}"
            );
        }

        $this->payment->markAsDone($paymentStatusRepo, $clock);
    }

    public function getSum(): Money
    {
        $total = null;
        foreach ($this->products as $product) {
            $total = $total
                ? $total->add($product->getSum())
                : $product->getSum();
        }

        if ($total === null) {
            throw new \LogicException(
                "The order {$this->id->toString()} doesn't have any product"
            );
        }

        return $total;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function getPayment(): ?OrderPayment
    {
        return $this->payment;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
