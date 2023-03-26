<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\UrlCreatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UrlCreator implements UrlCreatorInterface
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * @param string $name
     * @param array<string, mixed> $parameters
     * @param int|null $referenceType
     *
     * @return string
     */
    public function create(string $name, array $parameters = [], ?int $referenceType = null): string
    {
        if ($referenceType === null) {
            return $this->urlGenerator->generate($name, $parameters);
        }

        return $this->urlGenerator->generate($name, $parameters, $referenceType);
    }
}
