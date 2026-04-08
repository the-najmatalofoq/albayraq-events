<?php

declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\Money;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\ViolationType\Domain\Enum\ViolationSeverityEnum;

final class EloquentViolationTypeRepository implements ViolationTypeRepositoryInterface
{
    public function nextIdentity(): ViolationTypeId
    {
        return ViolationTypeId::generate();
    }

    public function save(ViolationType $violationType): void
    {
        ViolationTypeModel::query()->updateOrCreate(
            ['id' => $violationType->uuid->value],
            [
                'name'                       => $violationType->name->toArray(),
                'default_deduction_amount'   => $violationType->defaultDeduction?->amount,
                'default_deduction_currency' => $violationType->defaultDeduction?->currency,
                'severity'                   => $violationType->severity->value,
                'event_id'                   => $violationType->eventId?->value,
                'is_active'                  => $violationType->isActive,
            ]
        );
    }

    public function findById(ViolationTypeId $id): ?ViolationType
    {
        $record = ViolationTypeModel::find($id->value);

        return $record ? $this->toDomain($record) : null;
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = ViolationTypeModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(ViolationTypeModel $model) => $this->toDomain($model))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = ViolationTypeModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(ViolationTypeModel $model) => $this->toDomain($model));
    }

    public function delete(ViolationTypeId $id): void
    {
        ViolationTypeModel::query()->where('id', $id->value)->delete();
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where('name', 'like', "%{$criteria->search}%");
        }

        if ($criteria->has('severity')) {
            $query->where('severity', $criteria->get('severity'));
        }

        if ($criteria->has('is_active')) {
            $query->where('is_active', (bool)$criteria->get('is_active'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }

    private function toDomain(ViolationTypeModel $record): ViolationType
    {
        return ViolationType::create(
            uuid: ViolationTypeId::fromString($record->id),
            name: TranslatableText::fromArray($record->name),
            defaultDeduction: $record->default_deduction_amount 
                ? new Money((float)$record->default_deduction_amount, $record->default_deduction_currency ?? 'SAR') 
                : null,
            severity: ViolationSeverityEnum::from($record->severity),
            isActive: $record->is_active,
        );
    }
}
