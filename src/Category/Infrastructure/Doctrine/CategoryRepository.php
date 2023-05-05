<?php

declare(strict_types=1);

namespace App\Category\Infrastructure\Doctrine;

use App\Category\Domain\Category;
use App\Category\Domain\CategoryRepositoryInterface;
use App\Category\Domain\Exception\CategoryNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/** @template-extends ServiceEntityRepository<Category> */
final class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /** @throws Exception */
    public function save(Category $category): void
    {
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $em->persist($category);

            foreach ($category->getEvents() as $event) {
                $em->persist($event->getOutBoxMessage());
            }

            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();

            throw $e;
        }
    }

    public function findOneByUuid(string $uuid): ?Category
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    public function getOneByUuid(string $uuid): Category
    {
        $category = $this->findOneBy(['uuid' => $uuid]);

        if ($category === null) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }
}
