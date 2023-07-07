<?php

declare(strict_types=1);

namespace App\Domain\Order\Factory\DTO;

use App\Domain\Order\DTO\ResponseOrderProductDTO;
use App\Domain\Order\Entity\OrderProduct;

final class ProductFromEntityFactory
{
    public function create(OrderProduct $product): ResponseOrderProductDTO
    {
        return new ResponseOrderProductDTO(
            id: $product->getId()->toString(),
            name: $product->getName(),
            price: $product->getPrice()->getAmount(),
            count: $product->getCount(),
        );
    }

    /**
     * @param  OrderProduct[] $products
     * @return ResponseOrderProductDTO[]
     */
    public function createAll(iterable $products): array
    {
        $res = [];
        foreach ($products as $item) {
            $res[] = $this->create($item);
        }

        return $res;
    }
}
