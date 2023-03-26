<?php

declare(strict_types=1);

namespace App\Shared\Controller;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController
{
    public function __construct(protected ValidatorInterface $validator)
    {
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
