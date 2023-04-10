<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Symfony;

use App\Authentication\Domain\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as SymfonyUserPasswordHasherInterface;

final class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(private readonly SymfonyUserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function hashPassword(string $userUuid, ?string $userPassword, array $userRoles, string $password): string
    {
        $securityUser = new SecurityUser($userUuid, $userPassword, $userRoles);

        return $this->userPasswordHasher->hashPassword($securityUser, $password);
    }
}
