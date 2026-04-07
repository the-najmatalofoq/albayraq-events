<?php
declare(strict_types=1);

namespace Modules\User\Application\Command\RejectJoinRequest;

final readonly class RejectJoinRequestCommand
{
    public function __construct(
        public string $requestId,
        public string $reviewedBy,
        public ?string $notes = null,
    ) {}
}
