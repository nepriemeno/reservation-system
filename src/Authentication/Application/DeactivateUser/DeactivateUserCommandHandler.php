<?php

declare(strict_types=1);

namespace App\Authentication\Application\DeactivateUser;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserDeactivatedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;

class DeactivateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventBusInterface $bus,
    ) {
    }

    /** @throws UserNotFoundException */
    public function __invoke(DeactivateUserCommand $command): void
    {
        $uuid = $command->getUuid();
        $user = $this->userRepository->findOneByUuidActive($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $user->setIsActive(false);
        $this->userRepository->save($user);
        $this->bus->dispatch(new UserDeactivatedEvent($uuid));
    }
}
