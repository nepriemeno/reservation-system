<?php

declare(strict_types=1);

namespace App\Authentication\Application\CreateUser;

use App\Authentication\Domain\Exception\UserAlreadyExistsException;
use App\Authentication\Domain\User;
use App\Authentication\Domain\UserPasswordHasherInterface;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UuidCreatorInterface $uuidCreator,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    /** @throws UserAlreadyExistsException */
    public function __invoke(CreateUserCommand $command): void
    {
        $uuid = $this->uuidCreator->create();
        $email = $command->getEmail();
        $password = $command->getPassword();

        if ($this->userRepository->findOneByEmail($email) !== null) {
            throw new UserAlreadyExistsException();
        }

        $user = new User($uuid, $email);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }
}
