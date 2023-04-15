<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use App\Shared\Domain\OutboxMessage;
use App\Shared\Domain\OutboxMessageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @template-extends ServiceEntityRepository<OutboxMessage> */
final class OutboxMessageRepository extends ServiceEntityRepository implements OutboxMessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutboxMessage::class);
    }

    public function save(OutboxMessage $outboxMessage): void
    {
        $em = $this->getEntityManager();
        $em->persist($outboxMessage);
        $em->flush();
    }

    public function findUnprocessed(int $limit, string $domain): array
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('om')
            ->from(OutboxMessage::class, 'om')
            ->andWhere('om.processedAt IS NULL AND om.domain = :domain')
            ->setParameter('domain', $domain)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }
}
