<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

enum ExceptionCodeEnum: int
{
    case JsonEncode = 100;
    case BadRequest = 101;
}
