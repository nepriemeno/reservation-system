<?php

declare(strict_types=1);

namespace App\Category\Application\ChangeCategoryName;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Category\Domain\CategoryRepositoryInterface;
use App\Category\Domain\Exception\CategoryNotFoundException;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class ChangeCategoryNameCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws CategoryNotFoundException|UserNotFoundException */
    public function __invoke(ChangeCategoryNameCommand $command): void
    {
        $uuid = $command->getUuid();
        $name = $command->getName();
        $userUuid = $command->getUserUuid();
        $category = $this->categoryRepository->getOneByUuid($uuid);
        $this->userRepository->getOneByUuidActiveAdmin($userUuid);
        $category->changeName($name, $this->uuidCreator->create());
        $this->categoryRepository->save($category);
    }
}
