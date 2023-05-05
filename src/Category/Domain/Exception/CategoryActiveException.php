<?php

declare(strict_types=1);

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class CategoryActiveException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::CategoryActiveException->value,
            ExceptionCodeEnum::CategoryActiveException->value
        );
    }
}
