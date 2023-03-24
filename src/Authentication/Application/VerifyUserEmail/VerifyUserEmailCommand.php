<?php

declare(strict_types=1);

namespace App\Authentication\Application\VerifyUserEmail;

use App\Shared\Domain\Bus\Command\CommandInterface;

final class VerifyUserEmailCommand implements CommandInterface
{
    public function __construct(
        private readonly string $verificationSlug,
    ) {
    }

    public function getEmailVerificationSlug(): string
    {
        return $this->verificationSlug;
    }
}
