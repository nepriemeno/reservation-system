<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserPassword;

use App\Authentication\Domain\Exception\UserCurrentPasswordInvalidException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserPasswordHasherInterface;
use App\Authentication\Domain\UserPasswordValidatorInterface;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Doctrine\DBAL\Exception;

final class ChangeUserPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordValidatorInterface $userPasswordValidator,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    /** @throws UserNotFoundException|UserCurrentPasswordInvalidException|Exception */
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $uuid = $command->getUuid();
        $currentPassword = $command->getCurrentPassword();
        $password = $command->getPassword();
        $user = $this->userRepository->getOneByUuidActive($uuid);

        if (!$this->userPasswordValidator->isPasswordValid($user, $currentPassword)) {
            throw new UserCurrentPasswordInvalidException();
        }

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user->getUuid(),
            $user->getPassword(),
            $user->getRoles(),
            $password
        );
        $user->changePassword($hashedPassword);
        $this->userRepository->save($user);
    }
}
