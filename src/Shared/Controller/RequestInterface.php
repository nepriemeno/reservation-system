<?php

declare(strict_types=1);

namespace App\Shared\Controller;

use Symfony\Component\Validator\Constraints as Assert;

interface RequestInterface
{
    public static function getConstraint(): Assert\Collection;
}
