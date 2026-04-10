<?php
// modules/EventJoinRequest/Infrastructure/Persistence/Eloquent/EloquentEventJoinRequestRepository.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Infrastructure\Persistence\Eloquent;

use DateTimeImmutable;
use Modules\EventJoinRequest\Domain\EventJoinRequest;
use Modules\EventJoinRequest\Domain\Enum\JoinRequestStatusEnum;
use Modules\EventJoinRequest\Domain\Repository\EventJoinRequestRepositoryInterface;
use Modules\EventJoinRequest\Domain\ValueObject\JoinRequestId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final class EloquentEventJoinRequestRepository implements EventJoinRequestRepositoryInterface
{
    public function nextIdentity(): JoinRequestId
    {
        return JoinRequestId::generate();
    }

    public function save(EventJoinRequest $request): void
    {
        EventJoinRequestModel::updateOrCreate(
            ['id' => $request->uuid->value],
            [
                'user_id' => $request->userId->value,
                'event_id' => $request->eventId->value,
                'position_id' => $request->positionId->value,
                'status' => $request->status->value,
                'rejection_reason' => $request->rejectionReason,
                'reviewed_by' => $request->reviewedBy?->value,
                'reviewed_at' => $request->reviewedAt?->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function findById(JoinRequestId $id): ?EventJoinRequest
    {
        $model = EventJoinRequestModel::find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByUserAndEvent(UserId $userId, EventId $eventId): ?EventJoinRequest
    {
        $model = EventJoinRequestModel::where('user_id', $userId->value)
            ->where('event_id', $eventId->value)
            ->first();
        return $model ? $this->toEntity($model) : null;
    }

    /** @return EventJoinRequest[] */
    public function findByEventId(EventId $eventId): array
    {
        return EventJoinRequestModel::where('event_id', $eventId->value)
            ->get()
            ->map(fn(EventJoinRequestModel $m) => $this->toEntity($m))
            ->toArray();
    }

    /** @return EventJoinRequest[] */
    public function findByUserId(UserId $userId): array
    {
        return EventJoinRequestModel::where('user_id', $userId->value)
            ->get()
            ->map(fn(EventJoinRequestModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function delete(JoinRequestId $id): void
    {
        EventJoinRequestModel::where('id', $id->value)->delete();
    }

    private function toEntity(EventJoinRequestModel $m): EventJoinRequest
    {
        return EventJoinRequest::reconstitute(
            uuid: JoinRequestId::fromString($m->id),
            userId: UserId::fromString($m->user_id),
            eventId: EventId::fromString($m->event_id),
            positionId: PositionId::fromString($m->position_id),
            status: JoinRequestStatusEnum::from($m->status),
            createdAt: new DateTimeImmutable($m->created_at->toDateTimeString()),
            rejectionReason: $m->rejection_reason,
            reviewedBy: $m->reviewed_by ? UserId::fromString($m->reviewed_by) : null,
            reviewedAt: $m->reviewed_at ? new DateTimeImmutable($m->reviewed_at->toDateTimeString()) : null,
        );
    }
}
