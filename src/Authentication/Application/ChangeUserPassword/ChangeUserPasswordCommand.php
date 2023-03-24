<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserPassword;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ChangeUserPasswordCommand implements CommandInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $currentPassword,
        private readonly string $password,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
