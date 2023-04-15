<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface OutboxMessageRepositoryInterface
{
    public function save(OutboxMessage $outboxMessage): void;
    /**
     * @param int $limit
     * @param string $domain
     *
     * @return OutboxMessage[]
     */
    public function findUnprocessed(int $limit, string $domain): array;
}
