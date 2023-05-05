<?php

declare(strict_types=1);

namespace App\Category\Domain;

use App\Category\Domain\Exception\CategoryNotFoundException;

interface CategoryRepositoryInterface
{
    public function save(Category $category): void;
    public function findOneByUuid(string $uuid): ?Category;
    /**
     * @param string $uuid
     *
     * @return Category
     *
     * @throws CategoryNotFoundException
     */
    public function getOneByUuid(string $uuid): Category;
}
