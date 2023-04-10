<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Product\Domain\Exception\ProductActiveException;
use App\Product\Domain\Exception\ProductNotActiveException;
use App\Shared\Domain\AggregateRoot;
use DateTimeImmutable;

/** @final */
class Product extends AggregateRoot
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $userUuid,
        private string $name,
        private string $description,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ) {
    }

    public static function create(
        string $uuid,
        string $userUuid,
        string $name,
        string $description,
        string $eventUuid,
    ): self {
        $product = new self($uuid, $userUuid, $name, $description);
        $product->addEvent(new ProductCreatedEvent($eventUuid, $product->getUuid()));

        return $product;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /** @throws ProductActiveException */
    public function activate(string $eventUuid): void
    {
        if ($this->isActive()) {
            throw new ProductActiveException();
        }

        $this->setIsActive(true);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new ProductActivatedEvent($eventUuid, $this->getUuid()));
    }

    /** @throws ProductNotActiveException */
    public function deactivate(string $eventUuid): void
    {
        if (!$this->isActive()) {
            throw new ProductNotActiveException();
        }

        $this->setIsActive(false);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new ProductDeactivatedEvent($eventUuid, $this->getUuid()));
    }

    private function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    private function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
