<?php

declare(strict_types=1);

namespace Modules\User\Domain\Exception;

use Modules\Shared\Domain\Enum\ErrorCodeEnum;
use Modules\Shared\Domain\Exception\DomainException;

final class PendingUpdateRequestException extends DomainException
{
    /**
     * @param string $targetKey The translation key for the target (e.g., 'messages.targets.user_info')
     */
    public static function forTarget(string $targetKey): self
    {
        $exception = new self();
        $exception->messageKey = 'messages.errors.pending_update_request';
        $exception->messageParams = ['target' => __($targetKey)];
        
        return $exception;
    }

    public function getErrorCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::PENDING_UPDATE_REQUEST;
    }
}
