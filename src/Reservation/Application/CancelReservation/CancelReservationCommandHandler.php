<?php

declare(strict_types=1);

namespace App\Reservation\Application\CancelReservation;

use App\Authentication\Domain\UserRepositoryInterface;
use App\Reservation\Domain\ReservationRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class CancelReservationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws  */
    public function __invoke(CancelReservationCommand $command): void
    {
        $uuid = $command->getUuid();
        $buyerUuid = $command->getBuyerUuid();
        $reservation = $this->reservationRepository->getOneActiveByUuidAndBuyerUuid($uuid, $buyerUuid);
        $this->userRepository->getOneByUuidActive($buyerUuid);
        $reservation->cancel($this->uuidCreator->create());
        $this->reservationRepository->save($reservation);
    }
}
