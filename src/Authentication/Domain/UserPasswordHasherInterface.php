<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

interface UserPasswordHasherInterface
{
    /**
     * @param string $userUuid
     * @param string|null $userPassword
     * @param string[] $userRoles
     * @param string $password
     *
     * @return string
     */
    public function hashPassword(string $userUuid, ?string $userPassword, array $userRoles, string $password): string;
}
