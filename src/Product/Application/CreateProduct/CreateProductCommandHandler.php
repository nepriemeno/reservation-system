<?php

declare(strict_types=1);

namespace App\Product\Application\CreateProduct;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Product\Domain\Product;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class CreateProductCommandHandler implements CommandInterface
{
    public function __construct(
        private readonly UuidCreatorInterface $uuidCreator,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    /** @throws UserNotFoundException */
    public function __invoke(CreateProductCommand $command): void
    {
        $userUuid = $command->getUserUuid();
        $this->userRepository->getOneByUuidActive($userUuid);
        $name = $command->getName();
        $description = $command->getDescription();
        $uuid = $this->uuidCreator->create();
        $product = Product::create($uuid, $userUuid, $name, $description, $this->uuidCreator->create());
        $this->productRepository->save($product);
    }
}
