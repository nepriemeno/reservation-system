<?php

declare(strict_types=1);

namespace App\Category\Application\ActivateCategory;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Category\Domain\CategoryRepositoryInterface;
use App\Category\Domain\Exception\CategoryActiveException;
use App\Category\Domain\Exception\CategoryNotFoundException;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class ActivateCategoryCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws CategoryNotFoundException|CategoryActiveException|UserNotFoundException */
    public function __invoke(ActivateCategoryCommand $command): void
    {
        $uuid = $command->getUuid();
        $userUuid = $command->getUserUuid();
        $category = $this->categoryRepository->getOneByUuid($uuid);
        $this->userRepository->getOneByUuidActiveAdmin($userUuid);
        $category->activate($this->uuidCreator->create());
        $this->categoryRepository->save($category);
    }
}
