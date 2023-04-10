<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Symfony;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @param string $uuid
     * @param string|null $password
     * @param string[] $roles
     */
    public function __construct(
        private readonly string $uuid,
        private readonly ?string $password,
        private readonly array $roles,
    ) {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->uuid;
    }
}
