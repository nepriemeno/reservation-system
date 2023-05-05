<?php

declare(strict_types=1);

namespace App\Authentication\Application\GetUser;

use App\Authentication\Domain\User;
use App\Shared\Domain\Bus\Query\ResponseInterface;
use DateTimeImmutable;

final class GetUserResponse implements ResponseInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $email,
        private readonly bool $isActive,
        private readonly bool $isEmailVerified,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt,
    ) {
    }

    public static function createFromUser(User $user): self
    {
        return new self(
            $user->getUuid(),
            $user->getEmail(),
            $user->isActive(),
            $user->isEmailVerified(),
            $user->getCreatedAt(),
            $user->getUpdatedAt(),
        );
    }

    /**
     * @return array{
     *      uuid: string,
     *      email: string,
     *      isActive: bool,
     *      isEmailVerified: bool,
     *      createdAt: string,
     *      updatedAt: string
     * }
     */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'isActive' => $this->isActive,
            'isEmailVerified' => $this->isEmailVerified,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
            'updatedAt' => $this->updatedAt->format(DATE_ATOM),
        ];
    }
}
