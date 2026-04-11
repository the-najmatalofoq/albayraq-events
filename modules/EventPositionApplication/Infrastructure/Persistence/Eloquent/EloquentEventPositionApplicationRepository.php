<?php
// filePath: modules/EventPositionApplication/Infrastructure/Persistence/Eloquent/EloquentEventPositionApplicationRepository.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Infrastructure\Persistence\EventPositionApplicationReflector;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentEventPositionApplicationRepository implements EventPositionApplicationRepositoryInterface
{
    public function nextIdentity(): ApplicationId
    {
        return ApplicationId::generate();
    }

    public function save(EventPositionApplication $application): void
    {
        EventPositionApplicationModel::updateOrCreate(
            ['id' => $application->uuid->value],
            [
                'user_id' => $application->userId->value,
                'position_id' => $application->positionId->value,
                'status' => $application->status->value,
                'ranking_score' => $application->rankingScore,
                'applied_at' => $application->appliedAt->format('Y-m-d H:i:s'),
                'reviewed_at' => $application->reviewedAt?->format('Y-m-d H:i:s'),
                'reviewed_by' => $application->reviewedBy?->value,
                'deleted_at' => $application->deletedAt?->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function findById(ApplicationId $id): ?EventPositionApplication
    {
        $model = EventPositionApplicationModel::find($id->value);
        return $model ? EventPositionApplicationReflector::fromModel($model) : null;
    }

    public function findByIdWithTrashed(ApplicationId $id): ?EventPositionApplication
    {
        $model = EventPositionApplicationModel::withTrashed()->find($id->value);
        return $model ? EventPositionApplicationReflector::fromModel($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        return EventPositionApplicationModel::where('user_id', $userId->value)
            ->get()
            ->map(fn(EventPositionApplicationModel $m) => EventPositionApplicationReflector::fromModel($m))
            ->toArray();
    }

    public function findByPositionId(PositionId $positionId): array
    {
        return EventPositionApplicationModel::where('position_id', $positionId->value)
            ->get()
            ->map(fn(EventPositionApplicationModel $m) => EventPositionApplicationReflector::fromModel($m))
            ->toArray();
    }

    public function delete(ApplicationId $id): void
    {
        EventPositionApplicationModel::destroy($id->value);
    }

    public function hardDelete(ApplicationId $id): void
    {
        EventPositionApplicationModel::withTrashed()->where('id', $id->value)->forceDelete();
    }

    public function restore(ApplicationId $id): void
    {
        EventPositionApplicationModel::withTrashed()->where('id', $id->value)->restore();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters(EventPositionApplicationModel::query(), $criteria)->paginate($perPage);
    }

    public function all(FilterCriteria $criteria): Collection
    {
        return $this->applyFilters(EventPositionApplicationModel::query(), $criteria)
            ->get()
            ->map(fn($m) => EventPositionApplicationReflector::fromModel($m));
    }

    private function applyFilters(Builder $query, FilterCriteria $criteria): Builder
    {
        if ($criteria->has('user_id')) {
            $query->where('user_id', $criteria->get('user_id'));
        }
        if ($criteria->has('position_id')) {
            $query->where('position_id', $criteria->get('position_id'));
        }
        if ($criteria->has('status')) {
            $query->where('status', $criteria->get('status'));
        }
        if ($criteria->has('search')) {
            $query->where('status', 'like', "%{$criteria->get('search')}%");
        }
        if ($criteria->get('trashed') === 'only') {
            $query->onlyTrashed();
        } elseif ($criteria->get('trashed') === 'with') {
            $query->withTrashed();
        }
        if ($criteria->sortBy) {
            $query->orderBy($criteria->sortBy, $criteria->sortDirection);
        }
        return $query;
    }
}
