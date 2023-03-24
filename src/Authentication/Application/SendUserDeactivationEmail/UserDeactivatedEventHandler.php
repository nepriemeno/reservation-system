<?php

declare(strict_types=1);

namespace App\Authentication\Application\SendUserDeactivationEmail;

use App\Authentication\Domain\Exception\UserActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserDeactivatedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class UserDeactivatedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepositoryInterface $userRepository,
        private readonly string $sender,
    ) {
    }

    /** @throws UserNotFoundException|UserActiveException|TransportExceptionInterface */
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
            $mail = (new Email())
                ->from($this->sender)
                ->to($user->getEmail())
                ->subject('authentication.application.send_user_deactivation_email.account_deactivated')
                ->text('authentication.application.send_user_deactivation_email.account_deactivated');
            $this->mailer->send($mail);
        }
    }
}
