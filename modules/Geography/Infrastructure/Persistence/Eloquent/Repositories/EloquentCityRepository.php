<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\Geography\Domain\City;
use Modules\Geography\Domain\Repository\CityRepositoryInterface;
use Modules\Geography\Domain\ValueObject\CityId;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\CityModel;
use DateTimeImmutable;

final class EloquentCityRepository implements CityRepositoryInterface
{
    public function nextIdentity(): CityId
    {
        return CityId::generate();
    }

    public function findById(CityId $id): ?City
    {
        $model = CityModel::query()->find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByCountryId(CountryId $countryId): array
    {
        return CityModel::query()->where('country_id', $countryId->value)
            ->get()
            ->map(fn (CityModel $model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByStateId(StateId $stateId): array
    {
        return CityModel::query()->where('state_id', $stateId->value)
            ->get()
            ->map(fn (CityModel $model) => $this->toEntity($model))
            ->toArray();
    }

    private function toEntity(CityModel $model): City
    {
        return new City(
            new CityId($model->id),
            new CountryId($model->country_id),
            $model->state_id ? new StateId($model->state_id) : null,
            $model->name ?? [],
            new DateTimeImmutable($model->created_at->toDateTimeString()),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );
    }

    public function save(City $city): void
    {
        CityModel::query()->updateOrCreate(
            ['id' => $city->id()->value],
            [
                'country_id' => $city->countryId()->value,
                'state_id' => $city->stateId()?->value,
                'name' => $city->names(),
            ]
        );
    }

    public function delete(CityId $id): void
    {
        CityModel::query()->where('id', $id->value)->delete();
    }
}
