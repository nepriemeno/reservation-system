<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserEmail;

use App\Authentication\Domain\Exception\UserEmailAlreadyTakenException;
use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserEmailChangedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use DateTimeImmutable;
use Symfony\Component\Messenger\MessageBusInterface;

final class ChangeUserEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MessageBusInterface $bus,
    ) {
    }

    /** @throws UserEmailAlreadyTakenException|UserNotFoundException|UserNotActiveException */
    public function __invoke(ChangeUserEmailCommand $command): void
    {
        $uuid = $command->getUuid();
        $email = $command->getEmail();
        $user = $this->userRepository->findOneByUuid($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$user->getIsActive()) {
            throw new UserNotActiveException();
        }

        if ($this->userRepository->findOneByEmail($email) !== null) {
            throw new UserEmailAlreadyTakenException();
        }

        $user->setEmail($email);
        $user->setEmailVerificationSlugExpiresAt(new DateTimeImmutable());
        $user->setUpdatedAt(new DateTimeImmutable());
        $this->userRepository->save($user);
        $this->bus->dispatch(new UserEmailChangedEvent($uuid, $email));
    }
}
