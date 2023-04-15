<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\EventFactoryInterface;
use App\Shared\Domain\Exception\EventFactoryException;
use App\Shared\Domain\OutboxMessage;

final class UserEventFactory implements EventFactoryInterface
{
    public function createEventFromOutboxMessage(OutboxMessage $outboxMessage): EventInterface
    {
        return match ($outboxMessage->getType()) {
            UserActivatedEvent::class => UserActivatedEvent::createFromOutboxMessage($outboxMessage),
            UserCreatedEvent::class => UserCreatedEvent::createFromOutboxMessage($outboxMessage),
            UserDeactivatedEvent::class => UserDeactivatedEvent::createFromOutboxMessage($outboxMessage),
            UserEmailChangedEvent::class => UserEmailChangedEvent::createFromOutboxMessage($outboxMessage),
            default => throw new EventFactoryException(),
        };
    }
}
