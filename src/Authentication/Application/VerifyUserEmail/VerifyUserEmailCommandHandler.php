<?php

declare(strict_types=1);

namespace App\Authentication\Application\VerifyUserEmail;

use App\Authentication\Domain\Exception\UserEmailVerificationUrlInvalidException;
use App\Authentication\Domain\Exception\UserNotActiveException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use DateTimeImmutable;

final class VerifyUserEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /** @throws UserEmailVerificationUrlInvalidException|UserNotActiveException */
    public function __invoke(VerifyUserEmailCommand $command): void
    {
        $emailVerificationSlug = $command->getEmailVerificationSlug();
        $user = $this->userRepository->findOneByEmailVerificationSlug($emailVerificationSlug);

        if ($user === null || $user->getEmailVerificationSlugExpiresAt() <= new DateTimeImmutable()) {
            throw new UserEmailVerificationUrlInvalidException();
        }

        if (!$user->getIsActive()) {
            throw new UserNotActiveException();
        }

        $user->setEmailVerificationSlug(null);
        $user->setEmailVerificationSlugExpiresAt(null);
        $this->userRepository->save($user);
    }
}
