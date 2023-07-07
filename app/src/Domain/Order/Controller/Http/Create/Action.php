<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Create;

use App\Defaults;
use App\Domain\Event\Factory\EventCollectorFactory;
use App\Domain\Exception\ItemNotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\Order\Factory\DTO\OrderFromEntityFactory;
use App\Domain\Order\Factory\UseCase\CreateOrderCaseFactory;
use App\Domain\Order\UseCase\CreateOrder\CaseOrderDTO;
use App\Domain\Order\UseCase\CreateOrder\CaseProductDTO;
use App\Domain\Product\Repository\ProductRepo;
use App\Domain\User\UserProviderInterface;
use App\Infra\Routing\ActionInterface;
use App\Infra\Routing\Method;
use App\Infra\Routing\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class Action implements ActionInterface
{
    public function __construct(
        private ProductRepo $productRepository,
        private UserProviderInterface $userProvider,
        private EntityManagerInterface $entityManager,
        private CreateOrderCaseFactory $caseFactory,
        private OrderFromEntityFactory $orderFromEntityFactory,
        private EventCollectorFactory $eventCollectorFactory,
    ) {
    }

    public const NAME = 'create_order';
    public function getRoute(): Route
    {
        return new Route(
            self::NAME,
            Method::POST,
            '/orders',
            Request::class,
        );
    }

    public function __invoke(Request $request): Response
    {
        $reqProductDTOs = array_map(
            fn (array $i) => RequestProductDTO::fromArray($i),
            $request->products
        );

        if (!$reqProductDTOs) {
            throw new ValidationException(
                "It needs at least one product to create an order"
            );
        }

        if (count($reqProductDTOs) > Defaults::ORDER_PRODUCTS_MAX) {
            throw new ValidationException(sprintf(
                "It's not available to order more than %s SKUs per one order",
                Defaults::ORDER_PRODUCTS_MAX
            ));
        }

        $products = [];
        foreach ($reqProductDTOs as $dto) {
            $entity = $this->productRepository->findById($dto->id);
            if (!$entity) {
                throw new ItemNotFoundException(
                    "Product with id '{$dto->id}' not found"
                );
            }
            $products[] = new CaseProductDTO(
                product:  $entity,
                count: $dto->count
            );
        }

        $eventCollector = $this->eventCollectorFactory->create();
        $case = $this->caseFactory->create($eventCollector);
        $order = $case->process(
            new CaseOrderDTO(
                $this->userProvider->getCurrentUserId(),
                $products
            )
        );

        foreach ($order->getProducts() as $orderedProduct) {
            $this->entityManager->persist($orderedProduct);
        }
        $this->entityManager->persist($order);

        $eventCollector->flush();
        $this->entityManager->flush();

        return new Response(
            $this->orderFromEntityFactory->create($order)
        );
    }
}
