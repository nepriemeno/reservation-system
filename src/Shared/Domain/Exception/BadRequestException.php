<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class BadRequestException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::BadRequest->value,
            ExceptionCodeEnum::BadRequest->value
        );
    }
}
