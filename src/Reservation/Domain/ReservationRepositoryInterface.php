<?php

declare(strict_types=1);

namespace App\Reservation\Domain;

use App\Reservation\Domain\Exception\ReservationNotFoundException;

interface ReservationRepositoryInterface
{
    public function save(Reservation $reservation): void;
    /**
     * @param string $uuid
     *
     * @return Reservation
     *
     * @throws ReservationNotFoundException
     */
    public function getOneActiveByUuid(string $uuid): Reservation;
    /**
     * @param string $uuid
     * @param string $buyerUuid
     *
     * @return Reservation
     *
     * @throws ReservationNotFoundException
     */
    public function getOneActiveByUuidAndBuyerUuid(string $uuid, string $buyerUuid): Reservation;
}
