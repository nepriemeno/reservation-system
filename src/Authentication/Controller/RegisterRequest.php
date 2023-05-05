<?php

declare(strict_types=1);

namespace App\Authentication\Controller;

use App\Authentication\Application\CreateUser\CreateUserCommand;
use App\Shared\Controller\CommandRequestInterface;
use App\Shared\Controller\PostRequestInterface;
use App\Shared\Controller\RequestInterface;
use App\Shared\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterRequest implements RequestInterface, PostRequestInterface, CommandRequestInterface
{
    public function __construct(
        private readonly string $email,
        private readonly string $password,
    ) {
    }

    public static function createFromParameters(array $parameters): self
    {
        return new self($parameters['email'], $parameters['password']);
    }

    public static function getConstraint(): Assert\Collection
    {
        return new Assert\Collection([
            'email' => new Assert\Email(),
            'password' => new Assert\Length(['min' => 8]),
        ]);
    }

    public function getCommand(): CommandInterface
    {
        return new CreateUserCommand($this->email, $this->password);
    }
}
