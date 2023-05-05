<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Exception;

enum ExceptionCodeEnum: int
{
    case ReservationNotFoundException = 500;
}
