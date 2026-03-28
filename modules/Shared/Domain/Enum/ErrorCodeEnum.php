<?php
// modules/Shared/Domain/Enum/ErrorCodeEnum.php
declare(strict_types=1);

namespace Modules\Shared\Domain\Enum;

enum ErrorCodeEnum: string
{
    case NOT_FOUND = 'NOT_FOUND';
    case VALIDATION_FAILED = 'VALIDATION_FAILED';
    case UNAUTHORIZED = 'UNAUTHORIZED';
    case FORBIDDEN = 'FORBIDDEN';
    case INTERNAL_ERROR = 'INTERNAL_ERROR';
    case USER_ALREADY_EXISTS = 'USER_ALREADY_EXISTS';
    case INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';

    public function getHttpStatus(): int
    {
        return match ($this) {
            self::NOT_FOUND => 404,
            self::VALIDATION_FAILED => 422,
            self::UNAUTHORIZED => 401,
            self::FORBIDDEN => 403,
            self::INTERNAL_ERROR => 500,
            self::USER_ALREADY_EXISTS => 409,
            self::INVALID_CREDENTIALS => 401,
        };
    }
}
