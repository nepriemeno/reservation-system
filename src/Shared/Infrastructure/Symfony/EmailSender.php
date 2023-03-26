<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\EmailSenderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailSender implements EmailSenderInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /** @throws TransportExceptionInterface */
    public function send(string $from, string $to, string $subject, string $text): void
    {
        $mail = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($text);

        $this->mailer->send($mail);
    }
}
