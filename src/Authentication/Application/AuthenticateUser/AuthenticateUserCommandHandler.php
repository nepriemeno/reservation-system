<?php

declare(strict_types=1);

namespace App\Authentication\Application\AuthenticateUser;

use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\Exception\UserPasswordInvalidException;
use App\Authentication\Domain\JwtTokenCreatorInterface;
use App\Authentication\Domain\UserPasswordValidatorInterface;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

final class AuthenticateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordValidatorInterface $userPasswordValidator,
        private readonly JwtTokenCreatorInterface $jwtTokenCreator,
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

        if (!$this->userPasswordValidator->isPasswordValid($user, $password)) {
            throw new UserPasswordInvalidException();
        }

        return $this->jwtTokenCreator->create($user);
    }
}
