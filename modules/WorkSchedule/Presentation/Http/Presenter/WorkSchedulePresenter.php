<?php
// modules/WorkSchedule/Presentation/Http/Presenter/WorkSchedulePresenter.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Presentation\Http\Presenter;

use Modules\WorkSchedule\Domain\WorkSchedule;

final class WorkSchedulePresenter
{
    public static function fromDomain(WorkSchedule $workSchedule): array
    {
        return [
            'id'               => $workSchedule->uuid->value,
            'schedulable_id'   => $workSchedule->schedulableId,
            'schedulable_type' => $workSchedule->schedulableType,
            'date'             => $workSchedule->date->format('Y-m-d'),
            'start_time'       => $workSchedule->startTime,
            'end_time'         => $workSchedule->endTime,
            'is_active'        => $workSchedule->isActive,
        ];
    }
}
