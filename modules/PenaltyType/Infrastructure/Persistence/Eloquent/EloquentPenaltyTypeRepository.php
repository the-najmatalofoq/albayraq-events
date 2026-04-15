<?php
// modules/PenaltyType/Infrastructure/Persistence/Eloquent/EloquentPenaltyTypeRepository.php
declare(strict_types=1);

namespace Modules\PenaltyType\Infrastructure\Persistence\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\PenaltyType\Domain\Repository\PenaltyTypeRepositoryInterface;
use Modules\PenaltyType\Domain\PenaltyType;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentPenaltyTypeRepository implements PenaltyTypeRepositoryInterface
{
    public function nextIdentity(): PenaltyTypeId
    {
        return PenaltyTypeId::generate();
    }

    public function save(PenaltyType $penaltyType): void
    {
        PenaltyTypeModel::query()->updateOrCreate(
            ['id' => $penaltyType->uuid->value],
            [
                'name'      => $penaltyType->name->toArray(),
                'slug'      => $penaltyType->slug,
                'is_active' => $penaltyType->isActive,
            ]
        );
    }

    public function findById(PenaltyTypeId $id): ?PenaltyType
    {
        $record = PenaltyTypeModel::find($id->value);

        return $record ? $this->toDomain($record) : null;
    }

    public function listAll(): array
    {
        return PenaltyTypeModel::all()
            ->map(fn(PenaltyTypeModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = PenaltyTypeModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(PenaltyTypeModel $model) => $this->toDomain($model))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = PenaltyTypeModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(PenaltyTypeModel $model) => $this->toDomain($model));
    }

    public function delete(PenaltyTypeId $id): void
    {
        PenaltyTypeModel::query()->where('id', $id->value)->delete();
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where(function ($q) use ($criteria) {
                $q->where('name', 'like', "%{$criteria->search}%")
                  ->orWhere('slug', 'like', "%{$criteria->search}%");
            });
        }

        if ($criteria->has('is_active')) {
            $query->where('is_active', (bool)$criteria->get('is_active'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }

    private function toDomain(PenaltyTypeModel $record): PenaltyType
    {
        return PenaltyType::create(
            uuid: PenaltyTypeId::fromString($record->id),
            slug: $record->slug,
            name: $record->name,
            isActive: $record->is_active,
        );
    }
}
