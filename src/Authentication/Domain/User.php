<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use App\Authentication\Domain\Exception\UserActiveException;
use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Shared\Domain\AggregateRoot;
use DateTimeImmutable;

/** @final */
class User extends AggregateRoot
{
    public const ROLES = ['ROLE_USER'];

    /**
     * @param string $uuid
     * @param string $email
     * @param string $password
     * @param string[] $roles
     * @param string|null $emailVerificationSlug
     * @param DateTimeImmutable|null $emailVerificationSlugExpiresAt
     * @param bool $isActive
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     */
    public function __construct(
        private readonly string $uuid,
        private string $email,
        private string $password,
        private readonly array $roles = self::ROLES,
        private ?string $emailVerificationSlug = null,
        private ?DateTimeImmutable $emailVerificationSlugExpiresAt = null,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ) {
    }

    public static function create(string $uuid, string $email, string $password, string $eventUuid): self
    {
        $user = new self($uuid, $email, $password);
        $user->addEvent(new UserCreatedEvent($eventUuid, $uuid));

        return $user;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function changeEmail(string $email, string $emailVerificationSlug, string $eventUuid): void
    {
        $this->setEmail($email);
        $this->setEmailVerificationSlug($emailVerificationSlug);
        $this->setEmailVerificationSlugExpiresAt(new DateTimeImmutable('+30 minutes'));
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new UserEmailChangedEvent(
            $eventUuid,
            $this->getUuid(),
            $this->getEmail(),
            $emailVerificationSlug
        ));
    }

    public function verifyEmail(): void
    {
        $this->setEmailVerificationSlug(null);
        $this->setEmailVerificationSlugExpiresAt(null);
        $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function isEmailVerified(): bool
    {
        return $this->getEmailVerificationSlug() === null && $this->getEmailVerificationSlugExpiresAt() === null;
    }

    public function changePassword(string $password): void
    {
        $this->setPassword($password);
        $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function getEmailVerificationSlug(): ?string
    {
        return $this->emailVerificationSlug;
    }

    public function isEmailVerificationSlugValid(): bool
    {
        return $this->getEmailVerificationSlugExpiresAt() > new DateTimeImmutable();
    }

    public function getEmailVerificationSlugExpiresAt(): ?DateTimeImmutable
    {
        return $this->emailVerificationSlugExpiresAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /** @throws UserActiveException */
    public function activate(string $eventUuid): void
    {
        if ($this->isActive()) {
            throw new UserActiveException();
        }

        $this->setIsActive(true);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new UserActivatedEvent($eventUuid, $this->getUuid()));
    }

    /** @throws UserNotActiveException */
    public function deactivate(string $eventUuid): void
    {
        if (!$this->isActive()) {
            throw new UserNotActiveException();
        }

        $this->setIsActive(false);
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->addEvent(new UserDeactivatedEvent($eventUuid, $this->getUuid()));
    }

    private function setPassword(string $password): void
    {
        $this->password = $password;
    }

    private function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    private function setEmail(string $email): void
    {
        $this->email = $email;
    }

    private function setEmailVerificationSlug(?string $emailVerificationSlug): void
    {
        $this->emailVerificationSlug = $emailVerificationSlug;
    }

    private function setEmailVerificationSlugExpiresAt(?DateTimeImmutable $emailVerificationSlugExpiresAt): void
    {
        $this->emailVerificationSlugExpiresAt = $emailVerificationSlugExpiresAt;
    }

    private function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
