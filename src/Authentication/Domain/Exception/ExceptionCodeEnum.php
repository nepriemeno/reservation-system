<?php

declare(strict_types=1);

namespace App\Authentication\Domain\Exception;

enum ExceptionCodeEnum: int
{
    case InvalidPassword = 200;
    case UserNotFound = 201;
    case UserAlreadyExists = 202;
}
