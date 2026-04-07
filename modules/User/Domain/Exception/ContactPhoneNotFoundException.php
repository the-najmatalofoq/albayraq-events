<?php
declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Domain\Exception\DomainException;
use Modules\User\Domain\ValueObject\ContactPhoneId;

final class ContactPhoneNotFoundException extends DomainException
{
    public static function withId(ContactPhoneId $id): self
    {
        return new self("Contact phone with ID {$id->value} was not found.");
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::NOT_FOUND;
    }
}
