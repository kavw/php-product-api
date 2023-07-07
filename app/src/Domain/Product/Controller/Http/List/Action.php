<?php

declare(strict_types=1);

namespace App\Domain\Product\Controller\Http\List;

use App\Domain\Product\Factory\DTO\ProductFromEntityFactory;
use App\Domain\Product\Repository\ProductRepo;
use App\Infra\Routing\ActionInterface;
use App\Infra\Routing\Method;
use App\Infra\Routing\Route;

final readonly class Action implements ActionInterface
{
    public function __construct(
        private ProductRepo $repository,
        private ProductFromEntityFactory $productFactory,
    ) {
    }

    public function getRoute(): Route
    {
        return new Route(
            'products',
            Method::GET,
            '/',
        );
    }

    public function __invoke(): Response
    {
        return new Response(
            items: $this->productFactory->createAll(
                $this->repository->findAll()
            ),
        );
    }
}
