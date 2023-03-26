<?php

declare(strict_types=1);

namespace App\Authentication\Application\SendUserEmailVerificationEmail;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserEmailChangedEvent;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;
use App\Shared\Domain\EmailSenderInterface;
use App\Shared\Domain\Exception\JsonEncodeException;
use App\Shared\Domain\UrlCreatorInterface;

final class UserEmailChangedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly EmailSenderInterface $emailSender,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UrlCreatorInterface $urlCreator,
        private readonly string $secret,
        private readonly string $sender,
    ) {
    }

    /** @throws UserNotFoundException|JsonEncodeException */
    public function __invoke(UserEmailChangedEvent $emailChangedEvent): void
    {
        $uuid = $emailChangedEvent->getUuid();
        $email = $emailChangedEvent->getEmail();
        $user = $this->userRepository->findOneByUuidActive($uuid);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        $encodedData = json_encode([$uuid, $email]);

        if ($encodedData === false) {
            throw new JsonEncodeException();
        }

        $emailVerificationSlug = urlencode(
            base64_encode(hash_hmac('sha256', $encodedData, $this->secret, true))
        );
        $user->setEmailVerificationSlug($emailVerificationSlug);
        $this->userRepository->save($user);
        $this->emailSender->send(
            $this->sender,
            $email,
            'authentication.application.send_user_email_verification_email.verify_email',
            'authentication.application.send_user_email_verification_email.verification_url: ' .
            $this->urlCreator->create(
                'verify-user-email',
                ['verificationSlug' => $emailVerificationSlug],
                0
            )
        );
    }
}
