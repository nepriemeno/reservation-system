<?php

declare(strict_types=1);

namespace App\Reservation\Application\CompleteReservation;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class CompleteReservationCommand implements CommandInterface
{
    public function __construct(
        private readonly string $uuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
