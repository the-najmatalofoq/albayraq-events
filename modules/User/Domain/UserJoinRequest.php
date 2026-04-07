<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use DateTimeImmutable;
use Modules\Shared\Domain\Entity;
use Modules\Shared\Domain\Identity;
use Modules\User\Domain\Enum\JoinRequestStatusEnum;
use Modules\User\Domain\ValueObject\UserJoinRequestId;
use Modules\User\Domain\ValueObject\UserId;

final class UserJoinRequest extends Entity
{
    private function __construct(
        public readonly UserJoinRequestId $uuid,
        public readonly UserId $userId,
        public private(set) JoinRequestStatusEnum $status,
        public private(set) ?string $reviewedBy,
        public private(set) ?DateTimeImmutable $reviewedAt,
        public private(set) ?string $notes,
        public readonly DateTimeImmutable $createdAt,
        public private(set) ?DateTimeImmutable $updatedAt = null,
    ) {}

    public static function create(
        UserJoinRequestId $uuid,
        UserId $userId,
        DateTimeImmutable $createdAt,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            status: JoinRequestStatusEnum::Pending,
            reviewedBy: null,
            reviewedAt: null,
            notes: null,
            createdAt: $createdAt,
        );
    }

    public static function reconstitute(
        UserJoinRequestId $uuid,
        UserId $userId,
        JoinRequestStatusEnum $status,
        ?string $reviewedBy,
        ?DateTimeImmutable $reviewedAt,
        ?string $notes,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt = null,
    ): self {
        return new self(
            uuid: $uuid,
            userId: $userId,
            status: $status,
            reviewedBy: $reviewedBy,
            reviewedAt: $reviewedAt,
            notes: $notes,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function approve(string $reviewedBy, ?string $notes = null): void
    {
        $this->status = JoinRequestStatusEnum::Approved;
        $this->reviewedBy = $reviewedBy;
        $this->reviewedAt = new DateTimeImmutable();
        $this->notes = $notes;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function reject(string $reviewedBy, ?string $notes = null): void
    {
        $this->status = JoinRequestStatusEnum::Rejected;
        $this->reviewedBy = $reviewedBy;
        $this->reviewedAt = new DateTimeImmutable();
        $this->notes = $notes;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function isPending(): bool
    {
        return $this->status->isPending();
    }

    public function isApproved(): bool
    {
        return $this->status->isApproved();
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
