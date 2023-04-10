<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

enum ExceptionCodeEnum: int
{
    case ProductNotFound = 300;
    case ProductNotActive = 301;
    case ProductActive = 302;
}
