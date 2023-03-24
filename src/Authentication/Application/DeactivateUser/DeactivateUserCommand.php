<?php

declare(strict_types=1);

namespace App\Authentication\Application\DeactivateUser;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class DeactivateUserCommand implements CommandInterface
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
