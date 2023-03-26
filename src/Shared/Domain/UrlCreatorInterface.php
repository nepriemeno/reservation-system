<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface UrlCreatorInterface
{
    /**
     * @param string $name
     * @param array<string, mixed> $parameters
     * @param int|null $referenceType
     *
     * @return string
     */
    public function create(string $name, array $parameters = [], ?int $referenceType = null): string;
}
