<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ToggleJoinRequestStatus;

final readonly class ToggleJoinRequestStatusCommand
{
    public function __construct(
        public string $joinRequestId,
    ) {
    }
}
