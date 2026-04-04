<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\Geography\Domain\State;
use Modules\Geography\Domain\Repository\StateRepositoryInterface;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\StateModel;
use DateTimeImmutable;

// fix: use __construct in all the EloquentRepository 
final class EloquentStateRepository implements StateRepositoryInterface
{
    public function __construct(private readonly StateModel $model) {}
    public function findById(StateId $id): ?State
    {
        $model = StateModel::find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByCountryId(CountryId $countryId): array
    {
        return StateModel::where('country_id', $countryId->value)
            ->get()
            ->map(fn(StateModel $model) => $this->toEntity($model))
            ->toArray();
    }

    private function toEntity(StateModel $model): State
    {
        return new State(
            new StateId($model->id),
            new CountryId($model->country_id),
            $model->name ?? [],
            new DateTimeImmutable($model->created_at->toDateTimeString()),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );
    }
}
