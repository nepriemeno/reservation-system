<?php

declare(strict_types=1);

namespace App\Authentication\Application\GetUser;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use App\Shared\Domain\Bus\Query\ResponseInterface;

final class GetUserQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /** @throws UserNotFoundException */
    public function __invoke(GetUserQuery $query): ResponseInterface
    {
        $uuid = $query->getUuid();
        $user = $this->userRepository->getOneByUuid($uuid);

        return GetUserResponse::createFromUser($user);
    }
}
