<?php

declare(strict_types=1);

namespace App\Authentication\Application\SendUserDeactivationEmail;

use App\Authentication\Domain\Exception\UserActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserDeactivatedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;
use App\Shared\Domain\EmailSenderInterface;

final class UserDeactivatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly EmailSenderInterface $emailSender,
        private readonly UserRepositoryInterface $userRepository,
        private readonly string $sender,
    ) {
    }

    /** @throws UserNotFoundException|UserActiveException */
    public function __invoke(UserDeactivatedEvent $userDeactivatedEvent): void
    {
        $uuid = $userDeactivatedEvent->getUuid();
        $user = $this->userRepository->findOneByUuid($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if ($user->getIsActive()) {
            throw new UserActiveException();
        }

        if ($user->getIsEmailVerified()) {
            $this->emailSender->send(
                $this->sender,
                $user->getEmail(),
                'authentication.application.send_user_deactivation_email.account_deactivated',
                'authentication.application.send_user_deactivation_email.account_deactivated'
            );
        }
    }
}
