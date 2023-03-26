<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

interface UserPasswordValidatorInterface
{
    public function isPasswordValid(User $user, string $password): bool;
}
