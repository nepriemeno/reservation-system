<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Shared\Domain\Bus\Event\EventInterface;
use App\Shared\Domain\OutboxMessage;
use DateTimeImmutable;

final class ProductDeactivatedEvent implements EventInterface
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
            self::class,
            ['productUuid' => $this->getProductUuid()],
            new DateTimeImmutable(),
            null,
        );
    }
}
