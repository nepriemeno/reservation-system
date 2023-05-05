<?php

declare(strict_types=1);

namespace App\Category\Application\ActivateCategory;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ActivateCategoryCommand implements CommandInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $userUuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
