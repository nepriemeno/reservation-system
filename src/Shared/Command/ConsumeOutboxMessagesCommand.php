<?php

declare(strict_types=1);

namespace App\Shared\Command;

use App\Shared\Application\ConsumeOutboxMessages\ConsumeOutboxMessagesCommand as ConsumeApplicationCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:shared.command.consume_outbox_messages')]
final class ConsumeOutboxMessagesCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'amount',
            InputArgument::OPTIONAL,
            'The amount of messages that will be consumed',
            100,
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount = intval($input->getArgument('amount'));
        $this->bus->dispatch((new ConsumeApplicationCommand($amount)));

        return Command::SUCCESS;
    }
}
