<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\OutboxMessage;

interface EventInterface
{
    /** @throws EventCreateFromOutboxException */
    public static function createFromOutboxMessage(OutboxMessage $outboxMessage): self;
    public function getOutBoxMessage(): OutboxMessage;
}
