<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\SubmitUpdateRequest;

use Illuminate\Support\Str;
use Modules\User\Domain\UserUpdateRequest;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserUpdateRequestId;

final readonly class SubmitUpdateRequestCommand
{
    public function __construct(
        // public UserUpdateRequestId $uuid,
        public UserId $userId,
        public string $targetType,
        public string $targetId,
        public array $newData,
    ) {}

}
