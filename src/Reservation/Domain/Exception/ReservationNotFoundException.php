<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ReservationNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::ReservationNotFoundException->value,
            ExceptionCodeEnum::ReservationNotFoundException->value
        );
    }
}
