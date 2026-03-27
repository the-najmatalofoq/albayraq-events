<?php
// modules/EventParticipation/Infrastructure/Persistence/EventParticipationReflector.php
declare(strict_types=1);

namespace Modules\EventParticipation\Infrastructure\Persistence;

use Modules\EventParticipation\Domain\EventParticipation;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationStatus;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventStaffingGroup\Domain\ValueObject\GroupId;
use Modules\EventParticipation\Infrastructure\Persistence\Eloquent\EventParticipationModel;

final class EventParticipationReflector
{
    public static function fromModel(EventParticipationModel $model): EventParticipation
    {
        $reflection = new \ReflectionClass(EventParticipation::class);
        $participation = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => ParticipationId::fromString($model->id),
            'userId' => UserId::fromString($model->user_id),
            'eventId' => EventId::fromString($model->event_id),
            'positionId' => PositionId::fromString($model->position_id),
            'groupId' => $model->group_id ? GroupId::fromString($model->group_id) : null,
            'employeeNumber' => $model->employee_number,
            'status' => ParticipationStatus::from($model->status),
            'startedAt' => \DateTimeImmutable::createFromMutable($model->started_at),
            'endedAt' => $model->ended_at ? \DateTimeImmutable::createFromMutable($model->ended_at) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($participation, $value);
        }

        return $participation;
    }
}
