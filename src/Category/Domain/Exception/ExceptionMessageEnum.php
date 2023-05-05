<?php

declare(strict_types=1);

namespace App\Category\Domain\Exception;

enum ExceptionMessageEnum: string
{
    case CategoryNotFoundException = 'category.domain.exception.category_not_found';
    case CategoryActiveException = 'category.domain.exception.category_active';
    case CategoryNotActiveException = 'category.domain.exception.category_not_active';
}
