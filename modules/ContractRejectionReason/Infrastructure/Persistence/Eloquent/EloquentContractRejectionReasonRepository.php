<?php

declare(strict_types=1);

namespace Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

final class EloquentContractRejectionReasonRepository implements ContractRejectionReasonRepositoryInterface
{
    public function nextIdentity(): ContractRejectionReasonId
    {
        return ContractRejectionReasonId::generate();
    }

    public function save(ContractRejectionReason $record): void
    {
        ContractRejectionReasonModel::query()->updateOrCreate(
            ['id' => $record->uuid->value],
            [
                'reason' => $record->reason->toArray(),
                'is_active' => $record->isActive,
            ]
        );
    }

    public function findById(ContractRejectionReasonId $id): ?ContractRejectionReason
    {
        $model = ContractRejectionReasonModel::find($id->value);

        return $model ? $this->toDomain($model) : null;
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = ContractRejectionReasonModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(fn(ContractRejectionReasonModel $model) => $this->toDomain($model));

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = ContractRejectionReasonModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(ContractRejectionReasonModel $model) => $this->toDomain($model));
    }

    public function delete(ContractRejectionReasonId $id): void
    {
        ContractRejectionReasonModel::query()->where('id', $id->value)->delete();
    }

    private function applyCriteria($query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where('reason', 'like', "%{$criteria->search}%");
        }

        if ($criteria->has('is_active')) {
            $query->where('is_active', (bool)$criteria->get('is_active'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }

    private function toDomain(ContractRejectionReasonModel $model): ContractRejectionReason
    {
        return ContractRejectionReason::create(
            uuid: ContractRejectionReasonId::fromString($model->id),
            reason: TranslatableText::fromArray($model->reason),
            isActive: $model->is_active
        );
    }
}
