<?php
// modules/EventJoinRequest/Application/Command/ReviewJoinRequest/ReviewJoinRequestCommand.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Application\Command\ReviewJoinRequest;

final readonly class ReviewJoinRequestCommand
{
    public function __construct(
        public string $joinRequestId,
        public string $reviewerId,
        public bool $approved,
        public ?string $rejectionReason = null,
    ) {
    }
}
