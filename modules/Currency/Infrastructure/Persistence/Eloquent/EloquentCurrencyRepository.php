<?php
// modules/Currency/Infrastructure/Persistence/Eloquent/EloquentCurrencyRepository.php
declare(strict_types=1);

namespace Modules\Currency\Infrastructure\Persistence\Eloquent;

use Modules\Currency\Domain\Currency;
use Modules\Currency\Domain\ValueObject\CurrencyId;
use Modules\Currency\Domain\Repository\CurrencyRepositoryInterface;
use Modules\Currency\Infrastructure\Persistence\Eloquent\Models\CurrencyModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Illuminate\Database\Eloquent\Builder;

final class EloquentCurrencyRepository implements CurrencyRepositoryInterface
{
    public function nextIdentity(): CurrencyId
    {
        return CurrencyId::generate();
    }

    public function save(Currency $currency): void
    {
        CurrencyModel::updateOrCreate(
            ['id' => $currency->uuid->value],
            \Modules\Currency\Infrastructure\Persistence\CurrencyReflector::fromDomain($currency)
        );
    }

    public function findById(CurrencyId $id): ?Currency
    {
        $model = CurrencyModel::find($id->value);
        return $model instanceof CurrencyModel ? $this->toEntity($model) : null;
    }

    public function findAll(): array
    {
        return CurrencyModel::all()
            ->map(fn(CurrencyModel $m) => $this->toEntity($m))
            ->toArray();
    }

    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator
    {
        $query = CurrencyModel::query();
        $this->applyCriteria($query, $criteria);

        $paginator = $query->paginate($perPage);

        $paginator->setCollection(
            $paginator->getCollection()->map(fn(CurrencyModel $m) => $this->toEntity($m))
        );

        return $paginator;
    }

    public function all(FilterCriteria $criteria): Collection
    {
        $query = CurrencyModel::query();
        $this->applyCriteria($query, $criteria);

        return $query->get()->map(fn(CurrencyModel $m) => $this->toEntity($m));
    }

    public function delete(CurrencyId $id): void
    {
        CurrencyModel::where('id', $id->value)->delete();
    }

    private function applyCriteria(Builder $query, FilterCriteria $criteria): void
    {
        if ($criteria->search) {
            $query->where(function (Builder $q) use ($criteria) {
                $q->where('code', 'like', "%{$criteria->search}%")
                    ->orWhere('symbol', 'like', "%{$criteria->search}%")
                    ->orWhere('name', 'like', "%{$criteria->search}%");
            });
        }

        if ($criteria->has('is_active')) {
            $query->where('is_active', (bool)$criteria->get('is_active'));
        }

        $sortBy = $criteria->sortBy ?: 'created_at';
        $sortDir = $criteria->sortDirection ?: 'desc';
        $query->orderBy($sortBy, $sortDir);
    }

    private function toEntity(CurrencyModel $m): Currency
    {
        return \Modules\Currency\Infrastructure\Persistence\CurrencyReflector::fromModel($m);
    }
}
