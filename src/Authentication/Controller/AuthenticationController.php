<?php

declare(strict_types=1);

namespace App\Authentication\Controller;

use App\Authentication\Application\ActivateUser\ActivateUserCommand;
use App\Authentication\Application\AuthenticateUser\AuthenticateUserCommand;
use App\Authentication\Application\ChangeUserEmail\ChangeUserEmailCommand;
use App\Authentication\Application\ChangeUserPassword\ChangeUserPasswordCommand;
use App\Authentication\Application\CreateUser\CreateUserCommand;
use App\Authentication\Application\DeactivateUser\DeactivateUserCommand;
use App\Authentication\Application\VerifyUserEmail\VerifyUserEmailCommand;
use App\Shared\Controller\ApiController;
use App\Shared\Domain\Exception\BadRequestException;
use App\Shared\Domain\Exception\DomainException;
use http\Exception\UnexpectedValueException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[AsController]
final class AuthenticationController extends ApiController
{
    #[Route('/api/authentication/register', methods: ['POST'])]
    public function register(Request $request, MessageBusInterface $bus): JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true);

            if (!is_array($parameters)) {
                throw new BadRequestException();
            }

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
        } catch (DomainException $e) {
            return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
        }

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

    #[Route('/api/authentication/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, MessageBusInterface $bus): JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true);

            if (!is_array($parameters)) {
                throw new BadRequestException();
            }

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
            $command = new AuthenticateUserCommand($email, $password);
        } catch (DomainException $e) {
            return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
        }

        try {
            $token = $bus->dispatch($command)->last(HandledStamp::class)?->getResult() ?? null;

            if ($token === null) {
                throw new UnexpectedValueException();
            }
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof DomainException) {
                return new JsonResponse($previousException->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            throw $e;
        }

        return new JsonResponse(
            ['token' => $token],
            Response::HTTP_OK,
        );
    }

    #[Route('/api/authentication/change-user-password', methods: ['POST'])]
    public function changeUserPassword(
        TokenStorageInterface $tokenStorageInterface,
        JWTTokenManagerInterface $jwtManager,
        Request $request,
        MessageBusInterface $bus,
    ): JsonResponse {
        try {
            $parameters = json_decode($request->getContent(), true);

            if (!is_array($parameters)) {
                throw new BadRequestException();
            }

            $constraint = new Assert\Collection([
                'current_password' => new Assert\Length(['min' => 8]),
                'password' => new Assert\Length(['min' => 8]),
            ]);
            $errorMessages = $this->getErrorMessages($parameters, $constraint);

            if (count($errorMessages['errors']) > 0) {
                return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
            }

            $currentPassword = $parameters['current_password'];
            $password = $parameters['password'];

            $token = $tokenStorageInterface->getToken();

            if ($token === null) {
                throw new UnexpectedValueException();
            }

            $jwtPayload = $jwtManager->decode($token);

            if (!is_array($jwtPayload) || !isset($jwtPayload['uuid']) || !is_string($jwtPayload['uuid'])) {
                throw new UnexpectedValueException();
            }

            $uuid = $jwtPayload['uuid'];
            $command = new ChangeUserPasswordCommand($uuid, $currentPassword, $password);
        } catch (DomainException $e) {
            return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
        }

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
            'authentication.controller.change_user_password_successful',
            Response::HTTP_OK
        );
    }

    #[Route('/api/authentication/change-user-email', methods: ['POST'])]
    public function changeUserEmail(
        TokenStorageInterface $tokenStorageInterface,
        JWTTokenManagerInterface $jwtManager,
        Request $request,
        MessageBusInterface $bus
    ): JsonResponse {
        try {
            $parameters = json_decode($request->getContent(), true);

            if (!is_array($parameters)) {
                throw new BadRequestException();
            }

            $constraint = new Assert\Collection([
                'email' => new Assert\Length(['min' => 8]),
            ]);
            $errorMessages = $this->getErrorMessages($parameters, $constraint);

            if (count($errorMessages['errors']) > 0) {
                return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
            }

            $email = $parameters['email'];
            $token = $tokenStorageInterface->getToken();

            if ($token === null) {
                throw new UnexpectedValueException();
            }

            $jwtPayload = $jwtManager->decode($token);

            if (!is_array($jwtPayload) || !isset($jwtPayload['uuid']) || !is_string($jwtPayload['uuid'])) {
                throw new UnexpectedValueException();
            }

            $uuid = $jwtPayload['uuid'];
            $command = new ChangeUserEmailCommand($uuid, $email);
        } catch (DomainException $e) {
            return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
        }

        try {
            $bus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof DomainException) {
                return new JsonResponse($previousException->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return new JsonResponse(
            'authentication.controller.change_user_email_successful',
            Response::HTTP_OK
        );
    }

    #[Route('/api/authentication/verify-user-email/{verificationSlug}', name: 'verify-user-email', methods: ['GET'])]
    public function verifyUserEmail(MessageBusInterface $bus, string $verificationSlug): JsonResponse
    {
        $command = new VerifyUserEmailCommand($verificationSlug);

        try {
            $bus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof DomainException) {
                return new JsonResponse($previousException->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return new JsonResponse(
            'authentication.controller.verify_user_email_successful',
            Response::HTTP_OK
        );
    }

    #[Route('/api/authentication/deactivate-user', name: 'deactivate-user', methods: ['POST'])]
    public function deactivateUser(Request $request, MessageBusInterface $bus): JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true);

            if (!is_array($parameters)) {
                throw new BadRequestException();
            }

            $constraint = new Assert\Collection([
                'uuid' => new Assert\Length(['min' => 8]),
            ]);
            $errorMessages = $this->getErrorMessages($parameters, $constraint);

            if (count($errorMessages['errors']) > 0) {
                return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
            }

            $uuid = $parameters['uuid'];
            $command = new DeactivateUserCommand($uuid);
        } catch (DomainException $e) {
            return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
        }

        try {
            $bus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof DomainException) {
                return new JsonResponse($previousException->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return new JsonResponse(
            'authentication.controller.deactivate_user_successful',
            Response::HTTP_OK
        );
    }

    #[Route('/api/authentication/activate-user', name: 'activate-user', methods: ['POST'])]
    public function activateUser(Request $request, MessageBusInterface $bus): JsonResponse
    {
        try {
            $parameters = json_decode($request->getContent(), true);

            if (!is_array($parameters)) {
                throw new BadRequestException();
            }

            $constraint = new Assert\Collection([
                'uuid' => new Assert\Length(['min' => 8]),
            ]);
            $errorMessages = $this->getErrorMessages($parameters, $constraint);

            if (count($errorMessages['errors']) > 0) {
                return new JsonResponse($errorMessages, Response::HTTP_BAD_REQUEST);
            }

            $uuid = $parameters['uuid'];
            $command = new ActivateUserCommand($uuid);
        } catch (DomainException $e) {
            return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
        }

        try {
            $bus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof DomainException) {
                return new JsonResponse($previousException->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        return new JsonResponse(
            'authentication.controller.activate_user_successful',
            Response::HTTP_OK
        );
    }
}
