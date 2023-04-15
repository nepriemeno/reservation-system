<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class EventFactoryException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::EventFactory->value,
            ExceptionCodeEnum::EventFactory->value
        );
    }
}
