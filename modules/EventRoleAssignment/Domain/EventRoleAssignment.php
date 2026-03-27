<?php
// modules/EventRoleAssignment/Domain/EventRoleAssignment.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Domain;

use Modules\Shared\Domain\AggregateRoot;
use Modules\Shared\Domain\Identity;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\IAM\Domain\ValueObject\RoleId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;

final class EventRoleAssignment extends AggregateRoot
{
    public function __construct(
        public readonly AssignmentId $uuid,
        public readonly EventId $eventId,
        public readonly UserId $userId,
        public readonly RoleId $roleId
    ) {}

    public static function create(
        AssignmentId $uuid,
        EventId $eventId,
        UserId $userId,
        RoleId $roleId
    ): self {
        return new self($uuid, $eventId, $userId, $roleId);
    }

    public function id(): Identity
    {
        return $this->uuid;
    }
}
