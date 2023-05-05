<?php

declare(strict_types=1);

namespace App\Category\Domain\Event;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\OutboxMessage;
use DateTimeImmutable;

final class CategoryNameChangedEvent implements EventInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $categoryUuid,
    ) {
    }

    private function getUuid(): string
    {
        return $this->uuid;
    }

    private function getCategoryUuid(): string
    {
        return $this->categoryUuid;
    }

    public function getOutBoxMessage(): OutboxMessage
    {
        return new OutboxMessage(
            $this->getUuid(),
            'Category',
            self::class,
            ['categoryUuid' => $this->getCategoryUuid()],
            new DateTimeImmutable(),
            null,
        );
    }

    public static function createFromOutboxMessage(OutboxMessage $outboxMessage): EventInterface
    {
        if (
            $outboxMessage->getType() !== self::class ||
            !isset($outboxMessage->getContent()['categoryUuid']) ||
            !is_string($outboxMessage->getContent()['categoryUuid'])
        ) {
            throw new EventCreateFromOutboxException();
        }

        return new self($outboxMessage->getUuid(), $outboxMessage->getContent()['categoryUuid']);
    }
}
