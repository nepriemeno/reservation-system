<?php

declare(strict_types=1);

namespace App\Order\Application\CancelOrder;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class CancelOrderCommand implements CommandInterface
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $sellerUuid,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getSellerUuid(): string
    {
        return $this->sellerUuid;
    }
}
