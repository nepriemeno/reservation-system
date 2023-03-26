<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

interface JwtTokenCreatorInterface
{
    public function create(User $user): string;
}
