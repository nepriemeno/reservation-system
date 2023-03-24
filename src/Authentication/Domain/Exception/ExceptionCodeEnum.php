<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

enum ExceptionCodeEnum: int
{
    case InvalidUserPassword = 200;
    case UserNotFound = 201;
    case UserAlreadyExists = 202;
    case UserEmailAlreadyTaken = 203;
    case UserEmailVerificationUrlInvalid = 204;
    case UserPasswordInvalid = 205;
    case UserCurrentPasswordInvalid = 206;
    case UserNotActive = 207;
    case UserActive = 208;
}
