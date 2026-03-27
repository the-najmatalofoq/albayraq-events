<?php
// modules/EventRoleAssignment/Infrastructure/Persistence/EventRoleAssignmentReflector.php
declare(strict_types=1);

namespace Modules\EventRoleAssignment\Infrastructure\Persistence;

use Modules\EventRoleAssignment\Domain\EventRoleAssignment;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\IAM\Domain\ValueObject\RoleId;
use Modules\EventRoleAssignment\Infrastructure\Persistence\Eloquent\EventRoleAssignmentModel;

final class EventRoleAssignmentReflector
{
    public static function fromModel(EventRoleAssignmentModel $model): EventRoleAssignment
    {
        $reflection = new \ReflectionClass(EventRoleAssignment::class);
        $assignment = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => AssignmentId::fromString($model->id),
            'eventId' => EventId::fromString($model->event_id),
            'userId' => UserId::fromString($model->user_id),
            'roleId' => RoleId::fromString($model->role_id),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($assignment, $value);
        }

        return $assignment;
    }
}
