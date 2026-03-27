<?php
// modules/User/Domain/Exception/UserProfileNotFoundException.php
declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCode;
use Modules\User\Domain\ValueObject\UserProfileId;

final class UserProfileNotFoundException extends DomainException
{
    public static function withId(UserProfileId $id): self
    {
        return new self("User profile with ID '{$id->value}' not found.");
    }

    public function getErrorCode(): ErrorCode
    {
        return ErrorCode::NOT_FOUND;
    }
}
