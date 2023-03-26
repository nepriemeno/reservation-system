<?php

declare(strict_types=1);

namespace App\Authentication\Application\ActivateUser;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;

final class ActivateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /** @throws UserNotFoundException */
    public function __invoke(ActivateUserCommand $command): void
    {
        $uuid = $command->getUuid();
        $user = $this->userRepository->findOneByUuid($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$user->getIsActive()) {
            $user->setIsActive(true);
            $this->userRepository->save($user);
        }
    }
}
