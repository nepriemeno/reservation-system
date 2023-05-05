<?php

declare(strict_types=1);

namespace App\Category\Domain\Exception;

enum ExceptionCodeEnum: int
{
    case CategoryNotFoundException = 400;
    case CategoryActiveException = 401;
    case CategoryNotActiveException = 402;
}
