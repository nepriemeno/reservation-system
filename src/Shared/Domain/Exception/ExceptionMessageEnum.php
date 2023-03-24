<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

enum ExceptionMessageEnum: string
{
    case JsonEncode = 'shared.domain.exception.json_encode';
    case BadRequest = 'shared.domain.exception.bad_request';
}
