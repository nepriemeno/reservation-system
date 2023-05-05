<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Event;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\OutboxMessage;
use DateTimeImmutable;

final class ReservationCreatedEvent implements EventInterface
{

    public function __construct(
        private readonly string $uuid,
        private readonly string $reservationUuid,
    ) {
    }

    private function getUuid(): string
    {
        return $this->uuid;
    }

    private function getReservationUuid(): string
    {
        return $this->reservationUuid;
    }

    public static function createFromOutboxMessage(OutboxMessage $outboxMessage): EventInterface
    {
        if (
            $outboxMessage->getType() !== self::class ||
            !isset($outboxMessage->getContent()['reservationUuid']) ||
            !is_string($outboxMessage->getContent()['reservationUuid'])
        ) {
            throw new EventCreateFromOutboxException();
        }

        return new self($outboxMessage->getUuid(), $outboxMessage->getContent()['reservationUuid']);
    }

    public function getOutBoxMessage(): OutboxMessage
    {
        return new OutboxMessage(
            $this->getUuid(),
            'Reservation',
            self::class,
            ['reservationUuid' => $this->getReservationUuid()],
            new DateTimeImmutable(),
            null,
        );
    }
}
