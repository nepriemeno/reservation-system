<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UserNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::UserNotFound->value,
            ExceptionCodeEnum::UserNotFound->value
        );
    }
}
