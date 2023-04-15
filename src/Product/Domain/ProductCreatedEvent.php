<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\Exception\EventCreateFromOutboxException;
use App\Shared\Domain\OutboxMessage;
use DateTimeImmutable;

final class ProductCreatedEvent implements EventInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $productUuid,
    ) {
    }

    private function getUuid(): string
    {
        return $this->uuid;
    }

    private function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getOutBoxMessage(): OutboxMessage
    {
        return new OutboxMessage(
            $this->getUuid(),
            'Product',
            self::class,
            ['productUuid' => $this->getProductUuid()],
            new DateTimeImmutable(),
            null,
        );
    }

    public static function createFromOutboxMessage(OutboxMessage $outboxMessage): EventInterface
    {
        if (
            $outboxMessage->getType() !== self::class ||
            !isset($outboxMessage->getContent()['productUuid']) ||
            !is_string($outboxMessage->getContent()['productUuid'])
        ) {
            throw new EventCreateFromOutboxException();
        }

        return new self($outboxMessage->getUuid(), $outboxMessage->getContent()['productUuid']);
    }
}
