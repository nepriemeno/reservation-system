<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\Exception\EventFactoryException;

interface EventFactoryInterface
{
    /** @throws EventCreateFromOutboxException|EventFactoryException */
    public function createEventFromOutboxMessage(OutboxMessage $outboxMessage): EventInterface;
}
