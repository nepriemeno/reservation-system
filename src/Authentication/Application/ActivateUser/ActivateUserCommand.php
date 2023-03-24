<?php

declare(strict_types=1);

namespace App\Authentication\Application\ActivateUser;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ActivateUserCommand implements CommandInterface
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
