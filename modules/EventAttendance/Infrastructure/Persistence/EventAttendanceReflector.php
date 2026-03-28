<?php
// modules/EventAttendance/Infrastructure/Persistence/EventAttendanceReflector.php
declare(strict_types=1);

namespace Modules\EventAttendance\Infrastructure\Persistence;

use Modules\EventAttendance\Domain\EventAttendance;
use Modules\EventAttendance\Domain\ValueObject\AttendanceId;
use Modules\EventAttendance\Domain\Enum\AttendanceMethodEnum;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\GeoPoint;
use Modules\EventAttendance\Infrastructure\Persistence\Eloquent\EventAttendanceModel;
use DateTimeImmutable;

final class EventAttendanceReflector
{
    public static function fromModel(EventAttendanceModel $model): EventAttendance
    {
        $reflection = new \ReflectionClass(EventAttendance::class);
        $attendance = $reflection->newInstanceWithoutConstructor();

        $checkInLocation = null;
        if ($model->check_in_latitude && $model->check_in_longitude) {
            $checkInLocation = new GeoPoint((float)$model->check_in_latitude, (float)$model->check_in_longitude);
        }

        $checkOutLocation = null;
        if ($model->check_out_latitude && $model->check_out_longitude) {
            $checkOutLocation = new GeoPoint((float)$model->check_out_latitude, (float)$model->check_out_longitude);
        }

        $properties = [
            'uuid'              => AttendanceId::fromString($model->id),
            'participationId'   => ParticipationId::fromString($model->event_participation_id),
            'date'              => DateTimeImmutable::createFromInterface($model->date),
            'checkInAt'         => $model->check_in_at ? DateTimeImmutable::createFromInterface($model->check_in_at) : null,
            'checkOutAt'        => $model->check_out_at ? DateTimeImmutable::createFromInterface($model->check_out_at) : null,
            'checkInLocation'   => $checkInLocation,
            'checkOutLocation'  => $checkOutLocation,
            'method'            => AttendanceMethodEnum::from($model->method),
            'verifiedBy'        => $model->verified_by ? UserId::fromString($model->verified_by) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($attendance, $value);
        }

        return $attendance;
    }
}
