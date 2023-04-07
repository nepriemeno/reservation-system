<?php

declare(strict_types=1);

namespace App\Authentication\Infrastructure\Symfony;

use App\Authentication\Domain\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityUserProvider implements UserProviderInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UserNotFoundException();
    }

    public function supportsClass(string $class): bool
    {
        return SecurityUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findOneByEmailActive($identifier);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return new SecurityUser($user);
    }
}
