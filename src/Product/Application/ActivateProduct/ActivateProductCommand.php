<?php

declare(strict_types=1);

namespace App\Product\Application\ActivateProduct;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class ActivateProductCommand implements CommandInterface
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
