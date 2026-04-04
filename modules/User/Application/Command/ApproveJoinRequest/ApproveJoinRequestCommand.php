<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ApproveJoinRequest;

final readonly class ApproveJoinRequestCommand
{
    public function __construct(
        public string $joinRequestId,
        public string $reviewedBy,
        public ?string $notes = null,
    ) {
    }
}
