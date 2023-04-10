<?php

declare(strict_types=1);

namespace App\Shared\Application\ConsumeOutboxMessages;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\OutboxMessageRepositoryInterface;

final class ConsumeOutboxMessagesCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly OutboxMessageRepositoryInterface $outboxMessageRepository,
    ) {
    }


    public function __invoke(ConsumeOutboxMessagesCommand $command): void
    {
        $amount = $command->getAmount();
        $outboxMessages = $this->outboxMessageRepository->findUnprocessed($amount);

        dump($outboxMessages);
        die;
//        foreach ($outboxMessages as $outboxMessage) {
        ////            $outboxMessage->ge
//        }
    }
}
