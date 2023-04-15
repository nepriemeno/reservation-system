<?php

declare(strict_types=1);

namespace App\Product\Application\DeactivateProduct;

use App\Authentication\Domain\Exception\UserNotFoundException;
use App\Authentication\Domain\UserRepositoryInterface;
use App\Product\Domain\Exception\ProductNotActiveException;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\UuidCreatorInterface;

final class DeactivateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly UuidCreatorInterface $uuidCreator,
    ) {
    }

    /** @throws UserNotFoundException|ProductNotFoundException|ProductNotActiveException */
    public function __invoke(DeactivateProductCommand $command): void
    {
        $userUuid = $command->getUserUuid();
        $this->userRepository->getOneByUuidActive($userUuid);
        $productUuid = $command->getProductUuid();
        $product = $this->productRepository->getOneByUuid($productUuid);
        $product->deactivate($this->uuidCreator->create());
        $this->productRepository->save($product);
    }
}
