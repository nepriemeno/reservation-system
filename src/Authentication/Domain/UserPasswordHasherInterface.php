<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

interface UserPasswordHasherInterface
{
    public function hashPassword(User $user, string $password): string;
}
