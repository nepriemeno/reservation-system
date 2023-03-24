<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UserPasswordInvalidException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::UserPasswordInvalid->value,
            ExceptionCodeEnum::UserPasswordInvalid->value
        );
    }
}
