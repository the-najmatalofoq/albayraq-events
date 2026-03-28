<?php
// modules/Shared/Infrastructure/Persistence/WorkScheduleReflector.php
declare(strict_types=1);

namespace Modules\Shared\Infrastructure\Persistence;

use Modules\Shared\Domain\WorkSchedule;
use Modules\Shared\Domain\ValueObject\ScheduleId;
use Modules\Shared\Infrastructure\Persistence\Eloquent\WorkScheduleModel;

final class WorkScheduleReflector
{
    public static function fromModel(WorkScheduleModel $model): WorkSchedule
    {
        $reflection = new \ReflectionClass(WorkSchedule::class);
        $schedule = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => ScheduleId::fromString($model->id),
            'schedulableId'     => $model->schedulable_id,
            'schedulableType'   => $model->schedulable_type,
            'daysOfWeek'        => $model->days_of_week,
            'startTime'         => $model->start_time,
            'endTime'           => $model->end_time,
            'isActive'          => (bool) $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($schedule, $value);
        }

        return $schedule;
    }
}
