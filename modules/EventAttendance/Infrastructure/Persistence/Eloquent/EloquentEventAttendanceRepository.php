<?php
// modules/EventAttendance/Infrastructure/Persistence/Eloquent/EloquentEventAttendanceRepository.php
declare(strict_types=1);

namespace Modules\EventAttendance\Infrastructure\Persistence\Eloquent;

use Modules\EventAttendance\Domain\EventAttendance;
use Modules\EventAttendance\Domain\ValueObject\AttendanceId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EventAttendance\Domain\Repository\EventAttendanceRepositoryInterface;
use Modules\EventAttendance\Infrastructure\Persistence\EventAttendanceReflector;

final class EloquentEventAttendanceRepository implements EventAttendanceRepositoryInterface
{
    public function nextIdentity(): AttendanceId
    {
        return AttendanceId::generate();
    }

    public function save(EventAttendance $attendance): void
    {
        EventAttendanceModel::updateOrCreate(
            ['id' => $attendance->uuid->value],
            [
                'event_participation_id' => $attendance->participationId->value,
                'date' => $attendance->date->format('Y-m-d'),
                'check_in_at' => $attendance->checkInAt->format('Y-m-d H:i:s'),
                'check_out_at' => $attendance->checkOutAt?->format('Y-m-d H:i:s'),
                'check_in_latitude' => $attendance->checkInLocation?->latitude,
                'check_in_longitude' => $attendance->checkInLocation?->longitude,
                'check_out_latitude' => $attendance->checkOutLocation?->latitude,
                'check_out_longitude' => $attendance->checkOutLocation?->longitude,
                'method' => $attendance->method->value,
                'verified_by' => $attendance->verifiedBy?->value,
            ]
        );
    }

    public function findById(AttendanceId $id): ?EventAttendance
    {
        $model = EventAttendanceModel::find($id->value);
        return $model ? EventAttendanceReflector::fromModel($model) : null;
    }

    public function findByParticipationId(ParticipationId $participationId): array
    {
        return EventAttendanceModel::where('event_participation_id', $participationId->value)
            ->get()
            ->map(function (EventAttendanceModel $model) {
                return EventAttendanceReflector::fromModel($model);
            })
            ->toArray();
    }
}
