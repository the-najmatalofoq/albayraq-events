<?php
// modules/EventBreakRequest/Infrastructure/Persistence/BreakRequestReflector.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Infrastructure\Persistence;

use Carbon\CarbonImmutable;
use Modules\EventBreakRequest\Domain\BreakRequest;
use Modules\EventBreakRequest\Domain\ValueObject\BreakRequestId;
use Modules\EventBreakRequest\Domain\BreakRequestStatus;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventBreakRequest\Infrastructure\Persistence\Models\BreakRequestModel;

final class BreakRequestReflector
{
    public static function fromModel(BreakRequestModel $model): BreakRequest
    {
        return (new \ReflectionClass(BreakRequest::class))->newInstanceWithoutConstructor(...[
            'uuid'              => BreakRequestId::fromString($model->id),
            'participationId'   => ParticipationId::fromString($model->event_participation_id),
            'date'              => CarbonImmutable::parse($model->date),
            'startTime'         => CarbonImmutable::parse($model->start_time),
            'endTime'           => CarbonImmutable::parse($model->end_time),
            'durationMinutes'   => (int) $model->duration_minutes,
            'status'            => BreakRequestStatus::from($model->status),
            'requestedBy'       => UserId::fromString($model->requested_by),
            'approvedBy'        => $model->approved_by ? UserId::fromString($model->approved_by) : null,
            'approvedAt'        => $model->approved_at ? CarbonImmutable::parse($model->approved_at) : null,
            'rejectionReason'   => $model->rejection_reason,
            'coverEmployeeId'   => $model->cover_employee_id ? UserId::fromString($model->cover_employee_id) : null,
            'createdAt'         => CarbonImmutable::parse($model->created_at),
        ]);
    }
}
