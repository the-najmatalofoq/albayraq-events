<?php
// modules/WorkSchedule/Infrastructure/Persistence/WorkScheduleReflector.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Infrastructure\Persistence;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\WorkSchedule\Infrastructure\Persistence\Eloquent\WorkScheduleModel;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final class WorkScheduleReflector
{
    public static function fromModel(WorkScheduleModel $model): WorkSchedule
    {
        return WorkSchedule::create(
            uuid: ScheduleId::fromString($model->id),
            schedulableId: $model->schedulable_id,
            schedulableType: $model->schedulable_type,
            date: \DateTimeImmutable::createFromMutable($model->date),
            startTime: $model->start_time,
            endTime: $model->end_time,
            isActive: (bool) $model->is_active
        );
    }
}
