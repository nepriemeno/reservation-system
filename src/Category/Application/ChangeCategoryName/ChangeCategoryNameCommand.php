<?php

declare(strict_types=1);

namespace App\Category\Application\ChangeCategoryName;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ChangeCategoryNameCommand implements CommandInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $name,
        private readonly string $userUuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
