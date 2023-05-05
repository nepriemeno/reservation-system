<?php

declare(strict_types=1);

namespace App\Reservation\Application\CreateReservation;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class CreateReservationCommand implements CommandInterface
{
    public function __construct(
        private readonly string $productUuid,
        private readonly string $buyerUuid,
    ) {
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getBuyerUuid(): string
    {
        return $this->buyerUuid;
    }
}
