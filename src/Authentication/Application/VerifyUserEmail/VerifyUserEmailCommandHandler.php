<?php

declare(strict_types=1);

namespace App\Authentication\Application\VerifyUserEmail;

use App\Authentication\Domain\Exception\UserEmailVerificationUrlInvalidException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use Doctrine\DBAL\Exception;

final class VerifyUserEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /** @throws UserEmailVerificationUrlInvalidException|Exception
     */
    public function __invoke(VerifyUserEmailCommand $command): void
    {
        $emailVerificationSlug = $command->getEmailVerificationSlug();
        $user = $this->userRepository->findOneByEmailVerificationSlugActive($emailVerificationSlug);

        if ($user === null || $user->isEmailVerificationSlugValid()) {
            throw new UserEmailVerificationUrlInvalidException();
        }

        $user->verifyEmail();
        $this->userRepository->save($user);
    }
}
