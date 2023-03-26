<?php

declare(strict_types=1);

namespace App\Authentication\Domain;

use DateTimeImmutable;

final class User
{
    /**
     * @param string $uuid
     * @param string $email
     * @param string|null $password
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
        private ?string $password = null,
        private readonly array $roles = ['ROLE_USER'],
        private ?string $emailVerificationSlug = null,
        private ?DateTimeImmutable $emailVerificationSlugExpiresAt = null,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $this->setEmailVerificationSlugExpiresAt(new DateTimeImmutable());

        return $this;
    }

    public function verifyEmail(): void
    {
        $this->setEmailVerificationSlug(null);
        $this->setEmailVerificationSlugExpiresAt(null);
    }

    public function isEmailVerified(): bool
    {
        return $this->getEmailVerificationSlug() === null && $this->getEmailVerificationSlugExpiresAt() === null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getEmailVerificationSlug(): ?string
    {
        return $this->emailVerificationSlug;
    }

    public function setEmailVerificationSlug(?string $emailVerificationSlug): self
    {
        $this->emailVerificationSlug = $emailVerificationSlug;
        $this->setEmailVerificationSlugExpiresAt(new DateTimeImmutable('+30 minutes'));

        return $this;
    }

    public function getEmailVerificationSlugExpiresAt(): ?DateTimeImmutable
    {
        return $this->emailVerificationSlugExpiresAt;
    }

    public function setEmailVerificationSlugExpiresAt(?DateTimeImmutable $emailVerificationSlugExpiresAt): self
    {
        $this->emailVerificationSlugExpiresAt = $emailVerificationSlugExpiresAt;

        return $this;
    }

    public function isEmailVerificationSlugValid(): bool
    {
        return $this->getEmailVerificationSlugExpiresAt() < new DateTimeImmutable();
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function activate(): void
    {
        $this->setIsActive(true);
    }

    public function deactivate(): void
    {
        $this->setIsActive(true);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
