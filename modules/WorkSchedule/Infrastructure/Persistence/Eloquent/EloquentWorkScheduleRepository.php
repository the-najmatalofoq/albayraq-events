<?php
// modules/WorkSchedule/Infrastructure/Persistence/Eloquent/EloquentWorkScheduleRepository.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Infrastructure\Persistence\Eloquent;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\WorkSchedule\Infrastructure\Persistence\WorkScheduleReflector;
use Modules\Shared\Domain\ValueObject\ScheduleId;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

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

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = WorkScheduleModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn($model) => WorkScheduleReflector::fromModel($model))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = WorkScheduleModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn($model) => WorkScheduleReflector::fromModel($model));
    }

    private function applyCriteria(Builder $query, FilterCriteria $criteria): void
    {
        if ($criteria->has('schedulable_type')) {
            $query->where('schedulable_type', $criteria->get('schedulable_type'));
        }

        if ($criteria->has('schedulable_id')) {
            $query->where('schedulable_id', $criteria->get('schedulable_id'));
        }

        if ($criteria->has('is_active')) {
            $query->where('is_active', (bool)$criteria->get('is_active'));
        }

        if ($criteria->search) {
            $query->where('date', 'like', "%{$criteria->search}%");
        }

        $sortBy = $criteria->sortBy ?: 'date';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }

    public function delete(ScheduleId $id): void
    {
        WorkScheduleModel::destroy($id->value);
    }
}
