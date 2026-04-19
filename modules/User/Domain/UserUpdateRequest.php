<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\User\Domain\Enum\UpdateRequestStatus;
use Modules\User\Domain\ValueObject\UserId;
use Modules\User\Domain\ValueObject\UserUpdateRequestId;

final class UserUpdateRequest extends AggregateRoot
{
    public function __construct(
        public readonly UserUpdateRequestId $uuid,
        public readonly UserId $userId,
        public readonly string $targetType,
        public readonly string $targetId,
        public readonly array $newData,
        public UpdateRequestStatus $status = UpdateRequestStatus::PENDING,
        public ?string $rejectionReason = null,
        public ?string $reviewedBy = null,
        public ?\DateTimeImmutable $reviewedAt = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
    public function id(): UserUpdateRequestId
    {
        return $this->uuid;
    }
    public function approve(string $adminId): void
    {
        if ($this->status !== UpdateRequestStatus::PENDING) {
            throw new \DomainException("Only pending requests can be approved.");
        }
        $this->status = UpdateRequestStatus::APPROVED;
        $this->reviewedBy = $adminId;
        $this->reviewedAt = new \DateTimeImmutable();
    }

    public function reject(string $adminId, string $reason): void
    {
        if ($this->status !== UpdateRequestStatus::PENDING) {
            throw new \DomainException("Only pending requests can be rejected.");
        }
        $this->status = UpdateRequestStatus::REJECTED;
        $this->rejectionReason = $reason;
        $this->reviewedBy = $adminId;
        $this->reviewedAt = new \DateTimeImmutable();
    }
}
