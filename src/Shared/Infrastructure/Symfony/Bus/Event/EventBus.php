<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Bus\Event;

use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\Bus\Event\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->bus->dispatch($event);
    }
}
