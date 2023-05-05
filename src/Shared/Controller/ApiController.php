<?php

declare(strict_types=1);

namespace App\Shared\Controller;

use App\Shared\Domain\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController
{
    public function __construct(protected ValidatorInterface $validator)
    {
    }

    /** @throws BadRequestException */
    protected function getRequestParameters(Request $request): array
    {
        $parameters = json_decode($request->getContent(), true);

        if (!is_array($parameters)) {
            throw new BadRequestException();
        }

        return $parameters;
    }

    /**
     * @param array<string, string> $parameters
     * @param Collection $constraint
     *
     * @return array{
     *      message: 'validation_failed',
     *      errors: array<
     *          int,
     *          array{
     *              property: string,
     *              value: mixed,
     *              message: string|\Stringable
     *          }
     *      >
     * } $messages
     */
    protected function getErrorMessages(array $parameters, Collection $constraint): array
    {
        $violations = $this->validator->validate(
            $parameters,
            $constraint
        );
        $messages = ['message' => 'validation_failed', 'errors' => []];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $messages['errors'][] = [
                'property' => $violation->getPropertyPath(),
                'value' => $violation->getInvalidValue(),
                'message' => $violation->getMessage(),
            ];
        }

        return $messages;
    }
}
