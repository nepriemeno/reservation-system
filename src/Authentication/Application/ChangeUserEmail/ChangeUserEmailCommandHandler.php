<?php

declare(strict_types=1);

namespace App\Authentication\Application\ChangeUserEmail;

use App\Authentication\Domain\Exception\UserEmailAlreadyTakenException;
use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\JsonEncodeException;
use App\Shared\Domain\UuidCreatorInterface;
use Doctrine\DBAL\Exception;

final class ChangeUserEmailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
        private readonly string $secret,
    ) {
    }

    /** @throws UserEmailAlreadyTakenException|UserNotFoundException|JsonEncodeException|Exception */
    public function __invoke(ChangeUserEmailCommand $command): void
    {
        $uuid = $command->getUuid();
        $email = $command->getEmail();
        $user = $this->userRepository->getOneByUuidActive($uuid);

        if ($this->userRepository->findOneByEmail($email) !== null) {
            throw new UserEmailAlreadyTakenException();
        }

        $encodedData = json_encode([$uuid, $email]);

        if ($encodedData === false) {
            throw new JsonEncodeException();
        }

        $emailVerificationSlug = urlencode(
            base64_encode(hash_hmac('sha256', $encodedData, $this->secret, true))
        );

        $user->changeEmail($email, $emailVerificationSlug, $this->uuidCreator->create());
        $this->userRepository->save($user);
    }
}
