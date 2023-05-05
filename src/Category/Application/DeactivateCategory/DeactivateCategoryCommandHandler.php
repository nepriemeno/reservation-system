<?php

declare(strict_types=1);

namespace App\Category\Application\DeactivateCategory;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Category\Domain\CategoryRepositoryInterface;
use App\Category\Domain\Exception\CategoryNotActiveException;
use App\Category\Domain\Exception\CategoryNotFoundException;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class DeactivateCategoryCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws CategoryNotFoundException|CategoryNotActiveException|UserNotFoundException */
    public function __invoke(DeactivateCategoryCommand $command): void
    {
        $uuid = $command->getUuid();
        $userUuid = $command->getUserUuid();
        $category = $this->categoryRepository->getOneByUuid($uuid);
        $this->userRepository->getOneByUuidActiveAdmin($userUuid);
        $category->deactivate($this->uuidCreator->create());
        $this->categoryRepository->save($category);
    }
}
