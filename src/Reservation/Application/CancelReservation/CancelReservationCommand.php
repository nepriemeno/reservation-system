<?php

declare(strict_types=1);

namespace App\Reservation\Application\CancelReservation;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class CancelReservationCommand implements CommandInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $buyerUuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getBuyerUuid(): string
    {
        return $this->buyerUuid;
    }
}
