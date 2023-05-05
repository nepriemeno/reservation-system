<?php

declare(strict_types=1);

namespace App\Category\Application\CreateCategory;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Category\Domain\Category;
use App\Category\Domain\CategoryRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class CreateCategoryCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws UserNotFoundException */
    public function __invoke(CreateCategoryCommand $command): void
    {
        $name = $command->getName();
        $userUuid = $command->getUserUuid();
        $this->userRepository->getOneByUuidActiveAdmin($userUuid);
        $category = Category::create($this->uuidCreator->create(), $name, $this->uuidCreator->create());
        $this->categoryRepository->save($category);
    }
}
