<?php

declare(strict_types=1);

namespace App\Authentication\Controller;

use App\Authentication\Application\CreateUser\CreateUserCommand;
use App\Shared\Controller\ApiController;
use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

#[AsController]
final class AuthenticationController extends ApiController
{
    #[Route('/authentication/register', methods: ['POST'])]
    public function register(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $constraint = new Assert\Collection([
            'email' => new Assert\Email(),
            'password' => new Assert\Length(['min' => 8]),
        ]);
        $errorMessages = $this->getErrorMessages($parameters, $constraint);

        if (count($errorMessages['errors']) > 0) {
            return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
        }

        $email = $parameters['email'];
        $password = $parameters['password'];
        $command = new CreateUserCommand($email, $password);

        try {
            $bus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof DomainException) {
                return new JsonResponse($previousException->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            throw $e;
        }

        return new JsonResponse(
            'authentication.controller.registration_successful',
            Response::HTTP_CREATED
        );
    }
}
