<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Enum;

enum ErrorCode: string
{
    case VALIDATION_FAILED    = 'validation_failed';
    case UNAUTHENTICATED      = 'unauthenticated';
    case FORBIDDEN            = 'forbidden';
    case NOT_FOUND            = 'not_found';
    case TOKEN_MISMATCH       = 'token_mismatch';
    case USER_ALREADY_EXISTS  = 'user_already_exists';
    case INVALID_CREDENTIALS  = 'invalid_credentials';
    case SERVER_ERROR         = 'server_error';

    public function getHttpStatus(): int
    {
        return match($this) {
            self::VALIDATION_FAILED   => 422,
            self::UNAUTHENTICATED     => 401,
            self::FORBIDDEN           => 403,
            self::NOT_FOUND           => 404,
            self::TOKEN_MISMATCH      => 419,
            self::USER_ALREADY_EXISTS => 409,
            self::INVALID_CREDENTIALS => 401,
            self::SERVER_ERROR        => 500,
        };
    }
}
