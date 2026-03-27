<?php
// modules/EventAttendance/Infrastructure/Persistence/EventAttendanceReflector.php
declare(strict_types=1);

namespace Modules\EventAttendance\Infrastructure\Persistence;

use Modules\EventAttendance\Domain\EventAttendance;
use Modules\EventAttendance\Domain\ValueObject\AttendanceId;
use Modules\EventAttendance\Domain\ValueObject\AttendanceMethod;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\Shared\Domain\ValueObject\GeoPoint;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventAttendance\Infrastructure\Persistence\Eloquent\EventAttendanceModel;

final class EventAttendanceReflector
{
    public static function fromModel(EventAttendanceModel $model): EventAttendance
    {
        $reflection = new \ReflectionClass(EventAttendance::class);
        $attendance = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => AttendanceId::fromString($model->id),
            'participationId' => ParticipationId::fromString($model->event_participation_id),
            'date' => \DateTimeImmutable::createFromMutable($model->date),
            'checkInAt' => \DateTimeImmutable::createFromMutable($model->check_in_at),
            'checkOutAt' => $model->check_out_at ? \DateTimeImmutable::createFromMutable($model->check_out_at) : null,
            'checkInLocation' => ($model->check_in_latitude && $model->check_in_longitude)
                ? new GeoPoint($model->check_in_latitude, $model->check_in_longitude)
                : null,
            'checkOutLocation' => ($model->check_out_latitude && $model->check_out_longitude)
                ? new GeoPoint($model->check_out_latitude, $model->check_out_longitude)
                : null,
            'method' => AttendanceMethod::from($model->method),
            'verifiedBy' => $model->verified_by ? UserId::fromString($model->verified_by) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($attendance, $value);
        }

        return $attendance;
    }
}
