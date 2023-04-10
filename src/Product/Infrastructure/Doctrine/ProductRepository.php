<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Doctrine;

use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @template-extends ServiceEntityRepository<Product> */
final class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();
    }

    public function findOneByUuid(string $uuid): ?Product
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    /**
     * @param string $uuid
     *
     * @return Product
     *
     * @throws ProductNotFoundException
     */
    public function getOneByUuid(string $uuid): Product
    {
        $product = $this->findOneBy(['uuid' => $uuid]);

        if ($product === null) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findOneByUuidActive(string $uuid): ?Product
    {
        return $this->findOneBy(['uuid' => $uuid, 'isActive' => true]);
    }
}
