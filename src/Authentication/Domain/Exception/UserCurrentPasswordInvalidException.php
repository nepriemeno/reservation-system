<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UserCurrentPasswordInvalidException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::UserCurrentPasswordInvalid->value,
            ExceptionCodeEnum::UserCurrentPasswordInvalid->value
        );
    }
}
