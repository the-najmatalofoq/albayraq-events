<?php
// modules/Wage/Infrastructure/Persistence/Eloquent/EloquentWageRepository.php
declare(strict_types=1);

namespace Modules\Wage\Infrastructure\Persistence\Eloquent;

use Modules\Wage\Domain\Wage;
use Modules\Wage\Domain\ValueObject\WageId;
use Modules\Wage\Domain\Repository\WageRepositoryInterface;
use Modules\Wage\Infrastructure\Persistence\Eloquent\Models\WageModel;
use Modules\Shared\Domain\ValueObject\Money;

final class EloquentWageRepository implements WageRepositoryInterface
{
    public function nextIdentity(): WageId
    {
        return WageId::generate();
    }

    public function save(Wage $wage): void
    {
        WageModel::updateOrCreate(
            ['id' => $wage->uuid->value],
            [
                'wageable_id' => $wage->wageableId,
                'wageable_type' => $wage->wageableType,
                'amount' => $wage->amount->amount,
                'currency' => $wage->amount->currency,
                'currency_id' => $wage->currencyId,
                'period' => $wage->period,
            ]
        );
    }

    public function findById(WageId $id): ?Wage
    {
        $model = WageModel::find($id->value);
        return $model instanceof WageModel ? $this->toEntity($model) : null;
    }

    public function findByWageable(string $wageableId, string $wageableType): array
    {
        return WageModel::where('wageable_id', $wageableId)
            ->where('wageable_type', $wageableType)
            ->get()
            ->map(fn(WageModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function delete(WageId $id): void
    {
        WageModel::where('id', $id->value)->delete();
    }

    private function toEntity(WageModel $m): Wage
    {
        return Wage::reconstitute(
            uuid: WageId::fromString($m->id),
            wageableId: $m->wageable_id,
            wageableType: $m->wageable_type,
            amount: new Money((float) $m->amount, $m->currency),
            period: $m->period,
            currencyId: $m->currency_id,
        );
    }
}
