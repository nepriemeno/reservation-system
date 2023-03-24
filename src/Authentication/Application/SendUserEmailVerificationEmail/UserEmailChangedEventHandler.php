<?php

declare(strict_types=1);

namespace App\Authentication\Application\SendUserEmailVerificationEmail;

use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserEmailChangedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;
use App\Shared\Domain\Exception\JsonEncodeException;
use DateTimeImmutable;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UserEmailChangedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $secret,
        private readonly string $sender,
    ) {
    }

    /** @throws UserNotFoundException|UserNotActiveException|JsonEncodeException|TransportExceptionInterface */
    public function __invoke(UserEmailChangedEvent $emailChangedEvent): void
    {
        $uuid = $emailChangedEvent->getUuid();
        $email = $emailChangedEvent->getEmail();
        $user = $this->userRepository->findOneByUuid($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        if (!$user->getIsActive()) {
            throw new UserNotActiveException();
        }

        $encodedData = json_encode([$uuid, $email]);

        if ($encodedData === false) {
            throw new JsonEncodeException();
        }

        $emailVerificationSlug = urlencode(
            base64_encode(hash_hmac('sha256', $encodedData, $this->secret, true))
        );
        $user->setEmailVerificationSlug($emailVerificationSlug);
        $user->setEmailVerificationSlugExpiresAt(new DateTimeImmutable('+30 minutes'));
        $user->setUpdatedAt(new DateTimeImmutable());
        $this->userRepository->save($user);
        $mail = (new Email())
            ->from($this->sender)
            ->to($email)
            ->subject('authentication.application.send_user_email_verification_email.verify_email')
            ->text(
                'authentication.application.send_user_email_verification_email.verification_url: ' .
                    $this->urlGenerator->generate(
                        'verify-user-email',
                        ['verificationSlug' => $emailVerificationSlug],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
            );
        $this->mailer->send($mail);
    }
}
