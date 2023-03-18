<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findOneByUuid(string $uuid): ?User;
    public function findOneByEmail(string $email): ?User;
}