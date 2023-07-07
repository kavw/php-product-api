<?php

declare(strict_types=1);

namespace App\Domain\Product\Factory\DTO;

use App\Domain\Product\DTO\ResponseProductDTO;
use App\Domain\Product\Entity\Product;

final class ProductFromEntityFactory
{
    public function create(Product $product): ResponseProductDTO
    {
        return new ResponseProductDTO(
            id: $product->getId()->toString(),
            name: $product->getName(),
            price: $product->getPrice()->getAmount(),
        );
    }

    /**
     * @param  Product[] $products
     * @return ResponseProductDTO[]
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
