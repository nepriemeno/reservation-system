<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserEmail;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ChangeUserEmailCommand implements CommandInterface
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
