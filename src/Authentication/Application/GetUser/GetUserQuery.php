<?php

declare(strict_types=1);

namespace App\Authentication\Application\GetUser;

use App\Shared\Domain\Bus\Query\QueryInterface;

final class GetUserQuery implements QueryInterface
{
    public function __construct(
        private readonly string $uuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
