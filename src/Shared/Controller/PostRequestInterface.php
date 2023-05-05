<?php

declare(strict_types=1);

namespace App\Shared\Controller;

interface PostRequestInterface
{
    public static function createFromParameters(array $parameters): self;
}
