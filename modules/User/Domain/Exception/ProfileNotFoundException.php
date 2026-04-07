<?php
declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Domain\Exception\DomainException;
use Modules\User\Domain\ValueObject\UserId;

final class ProfileNotFoundException extends DomainException
{
    public static function forUser(UserId $id): self
    {
        return new self("Employee profile for user {$id->value} was not found.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
