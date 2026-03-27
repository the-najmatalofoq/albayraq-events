<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/Eloquent/EloquentEventRoleAssignmentRepository.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent;

use Modules\EventRoleAssignment\Domain\EventRoleAssignment;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventRoleAssignment\Domain\Repository\EventRoleAssignmentRepositoryInterface;
use Modules\EventRoleAssignment\Infrastructure\Persistence\EventRoleAssignmentReflector;

final class EloquentEventRoleAssignmentRepository implements EventRoleAssignmentRepositoryInterface
{
    public function nextIdentity(): AssignmentId
    {
        return AssignmentId::generate();
    }

    public function save(EventRoleAssignment $assignment): void
    {
        EventRoleAssignmentModel::updateOrCreate(
            ['id' => $assignment->uuid->value],
            [
                'event_id' => $assignment->eventId->value,
                'user_id' => $assignment->userId->value,
                'role_id' => $assignment->roleId->value,
            ]
        );
    }

    public function findById(AssignmentId $id): ?EventRoleAssignment
    {
        $model = EventRoleAssignmentModel::find($id->value);
        return $model ? EventRoleAssignmentReflector::fromModel($model) : null;
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventRoleAssignmentModel::where('event_id', $eventId->value)
            ->get()
            ->map(function (EventRoleAssignmentModel $model) {
                return EventRoleAssignmentReflector::fromModel($model);
            })
            ->toArray();
    }

    public function findByUserAndEvent(UserId $userId, EventId $eventId): array
    {
        return EventRoleAssignmentModel::where('user_id', $userId->value)
            ->where('event_id', $eventId->value)
            ->get()
            ->map(function (EventRoleAssignmentModel $model) {
                return EventRoleAssignmentReflector::fromModel($model);
            })
            ->toArray();
    }
}
