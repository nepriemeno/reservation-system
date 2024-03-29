<?php

declare(strict_types=1);

namespace App\Authentication\Application\SendUserDeactivationEmail;

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

    /** @throws UserNotFoundException */
    public function __invoke(UserDeactivatedEvent $userDeactivatedEvent): void
    {
        $uuid = $userDeactivatedEvent->getUserUuid();
        $user = $this->userRepository->getOneByUuid($uuid);

        if ($user->isEmailVerified()) {
            $this->emailSender->send(
                $this->sender,
                $user->getEmail(),
                'authentication.application.send_user_deactivation_email.account_deactivated',
                'authentication.application.send_user_deactivation_email.account_deactivated'
            );
        }
    }
}
