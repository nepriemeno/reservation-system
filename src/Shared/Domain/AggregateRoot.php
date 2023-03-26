<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;

abstract class AggregateRoot
{
    /** @var EventInterface[] */
    private array $domainEvents = [];

    /** @return EventInterface[] */
    public function getEvents(): array
    {
        return $this->domainEvents;
    }

    public function addEvent(EventInterface $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
