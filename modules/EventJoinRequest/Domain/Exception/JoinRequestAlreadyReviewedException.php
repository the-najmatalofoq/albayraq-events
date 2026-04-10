<?php
// modules/EventJoinRequest/Domain/Exception/JoinRequestAlreadyReviewedException.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Domain\Exception;

use Modules\Shared\Domain\Exception\DomainException;
use Modules\Shared\Domain\Enum\ErrorCodeEnum;

final class JoinRequestAlreadyReviewedException extends DomainException
{
    public static function create(string $detail = ''): self
    {
        return new self("Already reviewed: " . $detail);
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::VALIDATION_FAILED;
    }
}
