<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProductNotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::ProductNotFound->value,
            ExceptionCodeEnum::ProductNotFound->value
        );
    }
}
