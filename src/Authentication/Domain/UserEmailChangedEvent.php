<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
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
}
