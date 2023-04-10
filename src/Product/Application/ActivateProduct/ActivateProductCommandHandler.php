<?php

declare(strict_types=1);

namespace App\Product\Application\ActivateProduct;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Product\Domain\Exception\ProductActiveException;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class ActivateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws UserNotFoundException|ProductNotFoundException|ProductActiveException */
    public function __invoke(ActivateProductCommand $command): void
    {
        $userUuid = $command->getUserUuid();
        $this->userRepository->getOneByUuidActive($userUuid);
        $productUuid = $command->getProductUuid();
        $product = $this->productRepository->getOneByUuid($productUuid);
        $product->activate($this->uuidCreator->create());
        $this->productRepository->save($product);
    }
}
