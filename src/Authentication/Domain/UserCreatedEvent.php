<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\OutboxMessage;
use DateTimeImmutable;

final class UserCreatedEvent implements EventInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $userUuid,
    ) {
    }

    private function getUuid(): string
    {
        return $this->uuid;
    }

    private function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getOutBoxMessage(): OutboxMessage
    {
        return new OutboxMessage(
            $this->getUuid(),
            self::class,
            ['userUuid' => $this->getUserUuid()],
            new DateTimeImmutable(),
            null,
        );
    }
}
