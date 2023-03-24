<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserPassword;

use App\Authentication\Domain\Exception\UserCurrentPasswordInvalidException;
use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ChangeUserPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    /** @throws UserNotFoundException|UserCurrentPasswordInvalidException|UserNotActiveException */
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $uuid = $command->getUuid();
        $currentPassword = $command->getCurrentPassword();
        $password = $command->getPassword();
        $user = $this->userRepository->findOneByUuid($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$user->getIsActive()) {
            throw new UserNotActiveException();
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $currentPassword)) {
            throw new UserCurrentPasswordInvalidException();
        }

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $password
        );

        $user->setPassword($hashedPassword);
        $user->setUpdatedAt(new DateTimeImmutable());
        $this->userRepository->save($user);
    }
}
