<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Exception\ProductNotFoundException;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;
    public function findOneByUuid(string $uuid): ?Product;
    /**
     * @param string $uuid
     *
     * @return Product
     *
     * @throws ProductNotFoundException
     */
    public function getOneByUuid(string $uuid): Product;
    public function findOneByUuidActive(string $uuid): ?Product;
}
