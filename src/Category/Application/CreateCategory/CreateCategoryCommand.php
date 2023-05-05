<?php

declare(strict_types=1);

namespace App\Category\Application\CreateCategory;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class CreateCategoryCommand implements CommandInterface
{
    public function __construct(
        private readonly string $name,
        private readonly string $userUuid,
    ) {
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
