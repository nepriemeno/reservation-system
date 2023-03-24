<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;

final class UserDeactivatedEvent implements EventInterface
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
