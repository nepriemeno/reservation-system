<?php

declare(strict_types=1);

namespace App\Category\Domain;

use App\Category\Domain\Event\CategoryActivatedEvent;
use App\Category\Domain\Event\CategoryCreatedEvent;
use App\Category\Domain\Event\CategoryDeactivatedEvent;
use App\Category\Domain\Event\CategoryNameChangedEvent;
use App\Category\Domain\Exception\CategoryActiveException;
use App\Category\Domain\Exception\CategoryNotActiveException;
use App\Shared\Domain\AggregateRoot;
use DateTimeImmutable;

/** @final */
class Category extends AggregateRoot
{
    public function __construct(
        private readonly string $uuid,
        private string $name,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ) {
    }

    public static function create(string $uuid, string $name, string $eventUuid): self
    {
        $category = new self($uuid, $name);
        $category->addEvent(new CategoryCreatedEvent($eventUuid, $uuid));

        return $category;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
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

    /** @throws CategoryActiveException */
    public function activate(string $eventUuid): void
    {
        if ($this->isActive()) {
            throw new CategoryActiveException();
        }

        $this->setIsActive(true);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new CategoryActivatedEvent($eventUuid, $this->getUuid()));
    }

    /** @throws CategoryNotActiveException */
    public function deactivate(string $eventUuid): void
    {
        if (!$this->isActive()) {
            throw new CategoryNotActiveException();
        }

        $this->setIsActive(false);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new CategoryDeactivatedEvent($eventUuid, $this->getUuid()));
    }

    public function changeName(string $name, string $eventUuid): void
    {
        $this->setName($name);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new CategoryNameChangedEvent($eventUuid, $this->getUuid()));
    }

    private function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    private function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    private function setName(string $name): void
    {
        $this->name = $name;
    }
}
