<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

final class EventCreateFromOutboxException extends DomainException
{
    public function __construct()
    {
        parent::__construct(
            ExceptionMessageEnum::EventCreateFromOutbox->value,
            ExceptionCodeEnum::EventCreateFromOutbox->value
        );
    }
}
