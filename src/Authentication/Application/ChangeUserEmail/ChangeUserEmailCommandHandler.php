<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserEmail;

use App\Authentication\Domain\Exception\UserEmailAlreadyTakenException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserEmailChangedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;

final class ChangeUserEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $bus,
    ) {
    }

    /** @throws UserEmailAlreadyTakenException|UserNotFoundException */
    public function __invoke(ChangeUserEmailCommand $command): void
    {
        $uuid = $command->getUuid();
        $email = $command->getEmail();
        $user = $this->userRepository->findOneByUuidActive($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if ($this->userRepository->findOneByEmail($email) !== null) {
            throw new UserEmailAlreadyTakenException();
        }

        $user->setEmail($email);
        $this->userRepository->save($user);
        $this->bus->dispatch(new UserEmailChangedEvent($uuid, $email));
    }
}
