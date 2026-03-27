<?php
// modules/EventRoleAssignment/Domain/Repository/EventRoleAssignmentRepositoryInterface.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Domain\Repository;

use Modules\EventRoleAssignment\Domain\EventRoleAssignment;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\IAM\Domain\ValueObject\UserId;

interface EventRoleAssignmentRepositoryInterface
{
    public function nextIdentity(): AssignmentId;

    public function save(EventRoleAssignment $assignment): void;

    public function findById(AssignmentId $id): ?EventRoleAssignment;

    public function findByEventId(EventId $eventId): array;

    public function findByUserAndEvent(UserId $userId, EventId $eventId): array;
}
