<?php

declare(strict_types=1);

namespace App\Authentication\Application\AuthenticateUser;

use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\Exception\UserPasswordInvalidException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthenticateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly JWTTokenManagerInterface $JWTTokenManager,
    ) {
    }

    /** @throws UserNotFoundException|UserPasswordInvalidException|UserNotActiveException */
    public function __invoke(AuthenticateUserCommand $command): string
    {
        $email = $command->getEmail();
        $password = $command->getPassword();
        $user = $this->userRepository->findOneByEmail($email);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$user->getIsActive()) {
            throw new UserNotActiveException();
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $password)) {
            throw new UserPasswordInvalidException();
        }

        return $this->JWTTokenManager->create($user);
    }
}
