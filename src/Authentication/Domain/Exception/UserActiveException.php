<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class UserActiveException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::UserActive->value,
            ExceptionCodeEnum::UserActive->value
        );
    }
}
