<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\UuidCreatorInterface;
use Symfony\Component\Uid\Uuid;

final class UuidCreator implements UuidCreatorInterface
{
    public function create(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
