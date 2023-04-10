<?php

declare(strict_types=1);

namespace App\Product\DeactivateProduct;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class DeactivateProductCommand implements CommandInterface
{
    public function __construct(
        private readonly string $productUuid,
        private readonly string $userUuid,
    ) {
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
