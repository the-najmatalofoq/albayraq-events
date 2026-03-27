<?php
// modules/ContractRejectionReason/Infrastructure/Persistence/Eloquent/EloquentContractRejectionReasonRepository.php
declare(strict_types=1);

namespace Modules\ContractRejectionReason\Infrastructure\Persistence\Eloquent;

use Modules\ContractRejectionReason\Domain\ContractRejectionReason;
use Modules\ContractRejectionReason\Domain\ValueObject\ContractRejectionReasonId;
use Modules\ContractRejectionReason\Domain\Repository\ContractRejectionReasonRepositoryInterface;
use Modules\ContractRejectionReason\Infrastructure\Persistence\ContractRejectionReasonReflector;

final class EloquentContractRejectionReasonRepository implements ContractRejectionReasonRepositoryInterface
{
    public function nextIdentity(): ContractRejectionReasonId
    {
        return ContractRejectionReasonId::generate();
    }

    public function save(ContractRejectionReason $reason): void
    {
        ContractRejectionReasonModel::updateOrCreate(
            ['id' => $reason->uuid->value],
            [
                'reason' => $reason->reason->toArray(),
                'is_active' => $reason->isActive,
            ]
        );
    }

    public function findById(ContractRejectionReasonId $id): ?ContractRejectionReason
    {
        $model = ContractRejectionReasonModel::find($id->value);
        return $model ? ContractRejectionReasonReflector::fromModel($model) : null;
    }

    public function listAll(): array
    {
        return ContractRejectionReasonModel::all()
            ->map(fn($model) => ContractRejectionReasonReflector::fromModel($model))
            ->toArray();
    }
}
