<?php
// modules/ViolationType/Infrastructure/Persistence/Eloquent/EloquentViolationTypeRepository.php
declare(strict_types=1);

namespace Modules\ViolationType\Infrastructure\Persistence\Eloquent;

use Modules\ViolationType\Domain\ViolationType;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\ViolationType\Domain\Repository\ViolationTypeRepositoryInterface;
use Modules\ViolationType\Infrastructure\Persistence\ViolationTypeReflector;

final class EloquentViolationTypeRepository implements ViolationTypeRepositoryInterface
{
    public function nextIdentity(): ViolationTypeId
    {
        return ViolationTypeId::generate();
    }

    public function save(ViolationType $violationType): void
    {
        ViolationTypeModel::updateOrCreate(
            ['id' => $violationType->uuid->value],
            [
                'name' => $violationType->name->toArray(),
                'default_deduction_amount' => $violationType->defaultDeduction?->amount,
                'default_deduction_currency' => $violationType->defaultDeduction?->currency,
                'severity' => $violationType->severity->value,
                'is_active' => $violationType->isActive,
            ]
        );
    }

    public function findById(ViolationTypeId $id): ?ViolationType
    {
        $model = ViolationTypeModel::find($id->value);
        return $model ? ViolationTypeReflector::fromModel($model) : null;
    }

    public function listAll(): array
    {
        return ViolationTypeModel::all()
            ->map(fn($model) => ViolationTypeReflector::fromModel($model))
            ->toArray();
    }
}
