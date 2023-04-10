<?php

declare(strict_types=1);

namespace App\Product\Domain\Exception;

enum ExceptionMessageEnum: string
{
    case ProductNotFound = 'product.domain.exception.product_not_found';
    case ProductNotActive = 'product.domain.exception.product_not_active';
    case ProductActive = 'product.domain.exception.product_active';
}
