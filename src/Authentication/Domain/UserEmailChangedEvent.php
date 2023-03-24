<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;

final class UserEmailChangedEvent implements EventInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $email,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
