<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Symfony;

use App\Authentication\Domain\User;
use App\Authentication\Domain\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as SymfonyUserPasswordHasherInterface;

final class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(private readonly SymfonyUserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function hashPassword(User $user, string $password): string
    {
        return $this->userPasswordHasher->hashPassword($user, $password);
    }
}
