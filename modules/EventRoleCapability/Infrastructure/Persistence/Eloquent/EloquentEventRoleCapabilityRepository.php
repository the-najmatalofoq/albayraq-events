<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Eloquent/EloquentEventRoleCapabilityRepository.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Infrastructure\Persistence\EventRoleCapabilityReflector;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentEventRoleCapabilityRepository implements EventRoleCapabilityRepositoryInterface
{
    public function nextIdentity(): CapabilityId
    {
        return CapabilityId::generate();
    }

    public function save(EventRoleCapability $capability): void
    {
        EventRoleCapabilityModel::updateOrCreate(
            ['id' => $capability->uuid->value],
            [
                'event_role_assignment_id' => $capability->assignmentId->value,
                'capability_key' => $capability->capabilityKey,
                'is_granted' => $capability->isGranted,
                'deleted_at' => $capability->deletedAt?->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function findById(CapabilityId $id): ?EventRoleCapability
    {
        $model = EventRoleCapabilityModel::find($id->value);
        return $model ? EventRoleCapabilityReflector::fromModel($model) : null;
    }

    public function findByIdWithTrashed(CapabilityId $id): ?EventRoleCapability
    {
        $model = EventRoleCapabilityModel::withTrashed()->find($id->value);
        return $model ? EventRoleCapabilityReflector::fromModel($model) : null;
    }

    public function findByAssignmentId(AssignmentId $assignmentId): array
    {
        return EventRoleCapabilityModel::where('event_role_assignment_id', $assignmentId->value)
            ->get()
            ->map(fn($m) => EventRoleCapabilityReflector::fromModel($m))
            ->toArray();
    }

    public function delete(CapabilityId $id): void
    {
        EventRoleCapabilityModel::destroy($id->value);
    }

    public function hardDelete(CapabilityId $id): void
    {
        EventRoleCapabilityModel::withTrashed()->where('id', $id->value)->forceDelete();
    }

    public function restore(CapabilityId $id): void
    {
        EventRoleCapabilityModel::withTrashed()->where('id', $id->value)->restore();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->applyFilters(EventRoleCapabilityModel::query(), $criteria);

        return $query->paginate($perPage);
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = $this->applyFilters(EventRoleCapabilityModel::query(), $criteria);

        return $query->get()->map(fn($m) => EventRoleCapabilityReflector::fromModel($m));
    }

    private function applyFilters(Builder $query, FilterCriteria $criteria): Builder
    {
        if ($criteria->has('assignment_id')) {
            $query->where('event_role_assignment_id', $criteria->get('assignment_id'));
        }

        if ($criteria->has('capability_key')) {
            $query->where('capability_key', 'like', "%{$criteria->get('capability_key')}%");
        }

        // fix: do we handled the case that we don't need the trashed ?
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
