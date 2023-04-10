<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface OutboxMessageRepositoryInterface
{
    public function save(OutboxMessage $outboxMessage): void;
    /**
     * @param int $limit
     *
     * @return OutboxMessage[]
     */
    public function findUnprocessed(int $limit): array;
}
