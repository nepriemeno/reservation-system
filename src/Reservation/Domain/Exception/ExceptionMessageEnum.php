<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Exception;

enum ExceptionMessageEnum: string
{
    case ReservationNotFoundException = 'reservation.domain.exception.reservation_not_found';
}
