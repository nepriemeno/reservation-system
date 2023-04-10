<?php

declare(strict_types=1);

namespace App\Product\Application\CreateProduct;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class CreateProductCommand implements CommandInterface
{
    public function __construct(
        private readonly string $userUuid,
        private readonly string $name,
        private readonly string $description,
    ) {
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
}
