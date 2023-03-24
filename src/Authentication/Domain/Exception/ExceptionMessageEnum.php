<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

enum ExceptionMessageEnum: string
{
    case InvalidUserPassword = 'authentication.domain.exception.invalid_user_password';
    case UserNotFound = 'authentication.domain.exception.user_not_found';
    case UserAlreadyExists = 'authentication.domain.exception.user_already_exists';
    case UserEmailAlreadyTaken = 'authentication.domain.exception.user_email_already_taken';
    case UserEmailVerificationUrlInvalid = 'authentication.domain.exception.user_email_verification_url_invalid';
    case UserPasswordInvalid = 'authentication.domain.exception.user_password_invalid';
    case UserCurrentPasswordInvalid = 'authentication.domain.exception.user_current_password_invalid';
    case UserNotActive = 'authentication.domain.exception.user_not_active';
    case UserActive = 'authentication.domain.exception.user_active';
}
