<?php

declare(strict_types=1);

namespace App\Reservation\Domain;

use App\Reservation\Domain\Event\ReservationCreatedEvent;
use App\Shared\Domain\AggregateRoot;

/** @final */
final class Reservation extends AggregateRoot
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $productUuid,
        private readonly string $buyerUuid,
        private bool $isCanceled = true,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ) {
    }

    public static function create(
        string $uuid,
        string $productUuid,
        string $buyerUuid,
        string $eventUuid,
    ): self {
        $reservation = new self($uuid, $productUuid, $buyerUuid);
        $reservation->addEvent(new ReservationCreatedEvent($eventUuid, $reservation->getUuid()));

        return $reservation;
    }
}
