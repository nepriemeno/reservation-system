<?php

declare(strict_types=1);

namespace App\Shared\Controller;

use App\Shared\Domain\Bus\Command\CommandInterface;

interface CommandRequestInterface
{
    public function getCommand(): CommandInterface;
}
