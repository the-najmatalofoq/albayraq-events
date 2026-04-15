<?php
// modules/DeductionType/Infrastructure/Persistence/Eloquent/EloquentDeductionTypeRepository.php
declare(strict_types=1);

namespace Modules\DeductionType\Infrastructure\Persistence\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\DeductionType\Domain\Repository\DeductionTypeRepositoryInterface;
use Modules\DeductionType\Domain\DeductionType;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentDeductionTypeRepository implements DeductionTypeRepositoryInterface
{
    public function nextIdentity(): DeductionTypeId
    {
        return DeductionTypeId::generate();
    }

    public function save(DeductionType $deductionType): void
    {
        DeductionTypeModel::query()->updateOrCreate(
            ['id' => $deductionType->uuid->value],
            [
                'name'      => $deductionType->name->toArray(),
                'slug'      => $deductionType->slug,
                'is_active' => $deductionType->isActive,
            ]
        );
    }

    public function findById(DeductionTypeId $id): ?DeductionType
    {
        $record = DeductionTypeModel::find($id->value);

        return $record ? $this->toDomain($record) : null;
    }

    public function listAll(): array
    {
        return DeductionTypeModel::all()
            ->map(fn(DeductionTypeModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = DeductionTypeModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(DeductionTypeModel $model) => $this->toDomain($model))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = DeductionTypeModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(DeductionTypeModel $model) => $this->toDomain($model));
    }

    public function delete(DeductionTypeId $id): void
    {
        DeductionTypeModel::query()->where('id', $id->value)->delete();
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

    private function toDomain(DeductionTypeModel $record): DeductionType
    {
        return DeductionType::create(
            uuid: DeductionTypeId::fromString($record->id),
            slug: $record->slug,
            name: $record->name,
            isActive: $record->is_active,
        );
    }
}
