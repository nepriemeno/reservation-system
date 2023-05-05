<?php

declare(strict_types=1);

namespace App\Reservation\Application\CompleteReservation;

use App\Reservation\Domain\ReservationRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class CompleteReservationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws  */
    public function __invoke(CompleteReservationCommand $command): void
    {
        $uuid = $command->getUuid();
        $reservation = $this->reservationRepository->getOneActiveByUuid($uuid);
        $reservation->complete($this->uuidCreator->create());
        $this->reservationRepository->save($reservation);
    }
}
