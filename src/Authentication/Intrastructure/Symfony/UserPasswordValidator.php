<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Symfony;

use App\Authentication\Domain\User;
use App\Authentication\Domain\UserPasswordValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserPasswordValidator implements UserPasswordValidatorInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function isPasswordValid(User $user, string $password): bool
    {
        return $this->userPasswordHasher->isPasswordValid($user, $password);
    }
}
