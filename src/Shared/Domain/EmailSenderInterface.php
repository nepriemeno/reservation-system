<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface EmailSenderInterface
{
    public function send(string $from, string $to, string $subject, string $text): void;
}
