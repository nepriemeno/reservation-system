<?php

declare(strict_types=1);

namespace App\Authentication\Application\ConsumeOutboxMessages;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;
use App\Shared\Domain\EventFactoryInterface;
use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\Exception\EventFactoryException;
use App\Shared\Domain\OutboxMessageRepositoryInterface;

final class ConsumeOutboxMessagesCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly OutboxMessageRepositoryInterface $outboxMessageRepository,
        private readonly EventFactoryInterface $eventFactory,
        private readonly EventBusInterface $bus,
    ) {
    }

    /** @throws EventCreateFromOutboxException|EventFactoryException */
    public function __invoke(ConsumeOutboxMessagesCommand $command): void
    {
        $amount = $command->getAmount();
        $outboxMessages = $this->outboxMessageRepository->findUnprocessed($amount, 'Authentication');

        foreach ($outboxMessages as $outboxMessage) {
            $event = $this->eventFactory->createEventFromOutboxMessage($outboxMessage);
            $this->bus->dispatch($event);
        }
    }
}
