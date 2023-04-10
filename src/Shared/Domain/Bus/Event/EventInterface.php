<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Event;

use App\Shared\Domain\OutboxMessage;

interface EventInterface
{
    public function getOutBoxMessage(): OutboxMessage;
}
