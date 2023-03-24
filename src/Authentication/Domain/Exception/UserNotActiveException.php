<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UserNotActiveException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::UserNotActive->value,
            ExceptionCodeEnum::UserNotActive->value
        );
    }
}
