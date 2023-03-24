<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class JsonEncodeException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::JsonEncode->value,
            ExceptionCodeEnum::JsonEncode->value
        );
    }
}
