<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Pay;

use App\Domain\Event\Factory\EventCollectorFactory;
use App\Domain\Exception\ItemNotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\MoneyFactory;
use App\Domain\Order\Factory\DTO\OrderFromEntityFactory;
use App\Domain\Order\Factory\DTO\OrderPaymentFromEntityFactory;
use App\Domain\Order\Factory\Entity\OrderPaymentFactory;
use App\Domain\Order\Factory\UseCase\PayOrderCaseFactory;
use App\Domain\Order\Repository\OrderPaymentRepo;
use App\Domain\Order\Repository\OrderRepo;
use App\Infra\Exception\DomainException;
use App\Infra\Routing\ActionInterface;
use App\Infra\Routing\Method;
use App\Infra\Routing\Route;
use Doctrine\ORM\EntityManagerInterface;

final readonly class Action implements ActionInterface
{
    public const NAME = 'pay_order';

    public function __construct(
        private OrderRepo $orderRepository,
        private OrderPaymentRepo $orderPaymentRepo,
        private EntityManagerInterface $entityManager,
        private MoneyFactory $moneyFactory,
        private OrderPaymentFactory $paymentFactory,
        private PayOrderCaseFactory $caseFactory,
        private EventCollectorFactory $eventCollectorFactory,
        private OrderPaymentFromEntityFactory $paymentDTOFactory,
    ) {
    }

    public function getRoute(): Route
    {
        return new Route(
            self::NAME,
            Method::POST,
            '/order/{id}/payment',
            Request::class,
            arguments: ['id']
        );
    }

    public function __invoke(Request $request, string $orderId): Response
    {
        $order = $this->orderRepository->findById($orderId);
        if (!$order) {
            throw new ItemNotFoundException(
                "An order with id '{$orderId}' not found"
            );
        }

        if (!$request->sum) {
            throw new ValidationException(
                "The request requires the order sum field"
            );
        }

        $sum = $this->moneyFactory->create($request->sum);
        if (!$sum->equals($order->getSum())) {
            throw new ValidationException(
                "Sum mismatch for the order {$orderId}"
            );
        }

        if ($order->isPaid()) {
            return new Response(
                payment: $this->paymentDTOFactory->create(
                    $order->getPayment()
                    ?? throw new DomainException(
                        "The order {$orderId} that is mark as paid " .
                        "lost its payment"
                    )
                )
            );
        }

        $payment = $this->paymentFactory->create($order, $sum);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $this->entityManager->beginTransaction();
        try {
            $lockedPayment = $this->orderPaymentRepo
                ->findById($payment->getId(), lock: true);

            if (!$lockedPayment) {
                throw new \RuntimeException(
                    "The payment {$payment->getId()} " .
                    "for the order {$order->getId()} has been just lost"
                );
            }

            $eventCollector = $this->eventCollectorFactory->create();
            $case = $this->caseFactory->create($eventCollector);
            $processedPayment = $case->process($lockedPayment);

            $eventCollector->flush();
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        return new Response(
            payment: $this->paymentDTOFactory->create($processedPayment)
        );
    }
}
