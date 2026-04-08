<?php
// modules/WorkSchedule/Infrastructure/Persistence/Eloquent/EloquentWorkScheduleRepository.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Infrastructure\Persistence\Eloquent;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\WorkSchedule\Infrastructure\Persistence\WorkScheduleReflector;
use Modules\Shared\Domain\ValueObject\ScheduleId;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final class EloquentWorkScheduleRepository implements WorkScheduleRepositoryInterface
{
    public function nextIdentity(): ScheduleId
    {
        return ScheduleId::generate();
    }

    public function save(WorkSchedule $workSchedule): void
    {
        WorkScheduleModel::updateOrCreate(
            ['id' => $workSchedule->uuid->value],
            [
                'schedulable_id'   => $workSchedule->schedulableId,
                'schedulable_type' => $workSchedule->schedulableType,
                'date'             => $workSchedule->date->format('Y-m-d'),
                'start_time'       => $workSchedule->startTime,
                'end_time'         => $workSchedule->endTime,
                'is_active'        => $workSchedule->isActive,
            ]
        );
    }

    public function findById(ScheduleId $id): ?WorkSchedule
    {
        $model = WorkScheduleModel::find($id->value);
        return $model ? WorkScheduleReflector::fromModel($model) : null;
    }

    public function paginate(
        PaginationCriteria $criteria,
        ?string $schedulableType = null,
        ?string $schedulableId = null
    ): array {
        $query = WorkScheduleModel::query();

        if ($schedulableType !== null) {
            $query->where('schedulable_type', $schedulableType);
        }

        if ($schedulableId !== null) {
            $query->where('schedulable_id', $schedulableId);
        }

        $total = $query->count();
        $items = $query->offset($criteria->offset())
            ->limit($criteria->perPage)
            ->get()
            ->map(fn($model) => WorkScheduleReflector::fromModel($model))
            ->toArray();

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function delete(ScheduleId $id): void
    {
        WorkScheduleModel::destroy($id->value);
    }
}
