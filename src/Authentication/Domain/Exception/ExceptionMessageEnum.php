<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

enum ExceptionMessageEnum: string
{
    case InvalidPassword = 'authentication.domain.exception.invalid_password';
    case UserNotFound = 'authentication.domain.exception.user_not_found';
    case UserAlreadyExists = 'authentication.domain.exception.user_already_exists';
}
