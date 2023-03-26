<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Lexik;

use App\Authentication\Domain\JwtTokenCreatorInterface;
use App\Authentication\Domain\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class JwtTokenCreator implements JwtTokenCreatorInterface
{
    public function __construct(private readonly JWTTokenManagerInterface $JWTTokenManager)
    {
    }

    public function create(User $user): string
    {
        return $this->JWTTokenManager->create($user);
    }
}
