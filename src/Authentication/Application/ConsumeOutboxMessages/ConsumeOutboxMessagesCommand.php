<?php

declare(strict_types=1);

namespace App\Authentication\Application\ConsumeOutboxMessages;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ConsumeOutboxMessagesCommand implements CommandInterface
{
    public function __construct(private readonly int $amount)
    {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
