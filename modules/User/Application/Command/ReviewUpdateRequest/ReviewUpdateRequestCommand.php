<?php

declare(strict_types=1);

namespace Modules\User\Application\Command\ReviewUpdateRequest;

final readonly class ReviewUpdateRequestCommand
{
    public function __construct(
        public string $requestId,
        public string $adminId,
        public string $action, // 'approve' or 'reject'
        public ?string $rejectionReason = null,
    ) {
        if (!in_array($this->action, ['approve', 'reject'])) {
            throw new \InvalidArgumentException("Action must be 'approve' or 'reject'.");
        }
        if ($this->action === 'reject' && empty($this->rejectionReason)) {
            throw new \InvalidArgumentException("Rejection reason is required when rejecting.");
        }
    }
}
