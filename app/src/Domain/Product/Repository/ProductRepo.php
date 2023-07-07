<?php

declare(strict_types=1);

namespace App\Domain\Product\Repository;

use App\Domain\Product\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final readonly class ProductRepo
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function findById(string $id): ?Product
    {
        return $this->em->getRepository(Product::class)
            ->find($id);
    }

    /**
     * @return Product[]
     */
    public function findAll(): iterable
    {
        return $this->em->getRepository(Product::class)
            ->findAll();
    }
}
