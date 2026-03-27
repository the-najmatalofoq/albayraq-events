<?php
// modules/EventParticipation/Infrastructure/Persistence/Eloquent/EloquentEventParticipationRepository.php
declare(strict_types=1);

namespace Modules\EventParticipation\Infrastructure\Persistence\Eloquent;

use Modules\EventParticipation\Domain\EventParticipation;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventParticipation\Domain\Repository\EventParticipationRepositoryInterface;
use Modules\EventParticipation\Infrastructure\Persistence\EventParticipationReflector;

final class EloquentEventParticipationRepository implements EventParticipationRepositoryInterface
{
    public function nextIdentity(): ParticipationId
    {
        return ParticipationId::generate();
    }

    public function save(EventParticipation $participation): void
    {
        EventParticipationModel::updateOrCreate(
            ['id' => $participation->uuid->value],
            [
                'user_id' => $participation->userId->value,
                'event_id' => $participation->eventId->value,
                'position_id' => $participation->positionId->value,
                'group_id' => $participation->groupId?->value,
                'employee_number' => $participation->employeeNumber,
                'status' => $participation->status->value,
                'started_at' => $participation->startedAt->format('Y-m-d H:i:s'),
                'ended_at' => $participation->endedAt?->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function findById(ParticipationId $id): ?EventParticipation
    {
        $model = EventParticipationModel::find($id->value);
        return $model ? EventParticipationReflector::fromModel($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        return EventParticipationModel::where('user_id', $userId->value)
            ->get()
            ->map(function (EventParticipationModel $model) {
                return EventParticipationReflector::fromModel($model);
            })
            ->toArray();
    }

    public function findByEventId(EventId $eventId): array
    {
        return EventParticipationModel::where('event_id', $eventId->value)
            ->get()
            ->map(function (EventParticipationModel $model) {
                return EventParticipationReflector::fromModel($model);
            })
            ->toArray();
    }
}
