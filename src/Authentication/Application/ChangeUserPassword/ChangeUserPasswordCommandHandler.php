<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserPassword;

use App\Authentication\Domain\Exception\UserCurrentPasswordInvalidException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserPasswordHasherInterface;
use App\Authentication\Domain\UserPasswordValidatorInterface;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

final class ChangeUserPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordValidatorInterface $userPasswordValidator,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    /** @throws UserNotFoundException|UserCurrentPasswordInvalidException */
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $uuid = $command->getUuid();
        $currentPassword = $command->getCurrentPassword();
        $password = $command->getPassword();
        $user = $this->userRepository->findOneByUuidActive($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$this->userPasswordValidator->isPasswordValid($user, $currentPassword)) {
            throw new UserCurrentPasswordInvalidException();
        }

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }
}
