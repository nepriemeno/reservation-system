<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use DateTimeImmutable;

/** @final */
class OutboxMessage
{
    /**
     * @param string $uuid
     * @param string $type
     * @param array<string, mixed> $content
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $processedAt
     */
    public function __construct(
        private readonly string $uuid,
        private readonly string $type,
        private readonly array $content,
        private readonly DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $processedAt,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /** @return array<string, mixed> */
    public function getContent(): array
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getProcessedAt(): ?DateTimeImmutable
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?DateTimeImmutable $processedAt): void
    {
        $this->processedAt = $processedAt;
    }
}
