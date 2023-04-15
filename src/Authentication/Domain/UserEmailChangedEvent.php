<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\OutboxMessage;
use DateTimeImmutable;

final class UserEmailChangedEvent implements EventInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $userUuid,
        private readonly string $email,
        private readonly string $emailVerificationSlug,
    ) {
    }

    private function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailVerificationSlug(): string
    {
        return $this->emailVerificationSlug;
    }

    public function getOutBoxMessage(): OutboxMessage
    {
        return new OutboxMessage(
            $this->getUuid(),
            'Authentication',
            self::class,
            [
                'userUuid' => $this->getUserUuid(),
                'email' => $this->getEmail(),
                'emailVerificationSlug' => $this->getEmailVerificationSlug(),
            ],
            new DateTimeImmutable(),
            null,
        );
    }

    public static function createFromOutboxMessage(OutboxMessage $outboxMessage): EventInterface
    {
        if (
            $outboxMessage->getType() !== self::class ||
            !isset($outboxMessage->getContent()['userUuid']) ||
            !is_string($outboxMessage->getContent()['userUuid']) ||
            !isset($outboxMessage->getContent()['email']) ||
            !is_string($outboxMessage->getContent()['email']) ||
            !isset($outboxMessage->getContent()['emailVerificationSlug']) ||
            !is_string($outboxMessage->getContent()['emailVerificationSlug'])
        ) {
            throw new EventCreateFromOutboxException();
        }

        return new self(
            $outboxMessage->getUuid(),
            $outboxMessage->getContent()['userUuid'],
            $outboxMessage->getContent()['email'],
            $outboxMessage->getContent()['emailVerificationSlug']
        );
    }
}
