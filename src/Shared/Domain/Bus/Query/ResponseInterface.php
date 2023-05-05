<?php

declare(strict_types=1);

namespace App\Shared\Domain\Bus\Query;

interface ResponseInterface
{
    /** @return array<string, mixed> */
    public function toArray(): array;
}
