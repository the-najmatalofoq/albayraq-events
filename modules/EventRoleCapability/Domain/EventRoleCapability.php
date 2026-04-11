<?php
// modules/EventRoleCapability/Domain/EventRoleCapability.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;

final class EventRoleCapability extends AggregateRoot
{
    public function __construct(
        public readonly CapabilityId $uuid,
        public readonly AssignmentId $assignmentId,
        public private(set) string $capabilityKey,
        public private(set) bool $isGranted = true,
        public private(set) ?\DateTimeImmutable $deletedAt = null
    ) {}

    public static function create(
        CapabilityId $uuid,
        AssignmentId $assignmentId,
        string $capabilityKey,
        bool $isGranted = true
    ): self {
        return new self($uuid, $assignmentId, $capabilityKey, $isGranted);
    }

    public static function reconstitute(
        CapabilityId $uuid,
        AssignmentId $assignmentId,
        string $capabilityKey,
        bool $isGranted = true,
        ?\DateTimeImmutable $deletedAt = null
    ): self {
        return new self($uuid, $assignmentId, $capabilityKey, $isGranted, $deletedAt);
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function restore(): void
    {
        $this->deletedAt = null;
    }

    public function grant(): void
    {
        $this->isGranted = true;
    }

    public function revoke(): void
    {
        $this->isGranted = false;
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
