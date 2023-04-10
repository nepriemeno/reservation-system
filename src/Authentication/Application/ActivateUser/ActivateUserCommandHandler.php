<?php

declare(strict_types=1);

namespace App\Authentication\Application\ActivateUser;

use App\Authentication\Domain\Exception\UserActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;
use Doctrine\DBAL\Exception;

final class ActivateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws UserNotFoundException|UserActiveException|Exception
     */
    public function __invoke(ActivateUserCommand $command): void
    {
        $uuid = $command->getUuid();
        $user = $this->userRepository->getOneByUuid($uuid);
        $user->activate($this->uuidCreator->create());
        $this->userRepository->save($user);
    }
}
