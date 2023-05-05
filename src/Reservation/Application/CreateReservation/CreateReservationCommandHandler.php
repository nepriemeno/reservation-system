<?php

declare(strict_types=1);

namespace App\Reservation\Application\CreateReservation;

use App\Authentication\Domain\UserRepositoryInterface;
use App\Product\Domain\ProductRepositoryInterface;
use App\Reservation\Domain\ReservationRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class CreateReservationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws  */
    public function __invoke(CreateReservationCommand $command): void
    {
        $productUuid = $command->getProductUuid();
        $buyerUuid = $command->getBuyerUuid();
        $this->reservationRepository->save($reservation);
    }
}
