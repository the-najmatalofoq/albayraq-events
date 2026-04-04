<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\Geography\Domain\Nationality;
use Modules\Geography\Domain\Repository\NationalityRepositoryInterface;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\NationalityModel;
use DateTimeImmutable;

final class EloquentNationalityRepository implements NationalityRepositoryInterface
{
    public function nextIdentity(): NationalityId
    {
        return NationalityId::generate();
    }

    public function findById(NationalityId $id): ?Nationality
    {
        $model = NationalityModel::query()->find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findActiveByCountryId(CountryId $countryId): array
    {
        return NationalityModel::query()->where('country_id', $countryId->value)
            ->where('is_active', true)
            ->get()
            ->map(fn(NationalityModel $model) => $this->toEntity($model))
            ->toArray();
    }

    public function findAllActive(): array
    {
        return NationalityModel::query()->where('is_active', true)
            ->get()
            ->map(fn(NationalityModel $model) => $this->toEntity($model))
            ->toArray();
    }

    private function toEntity(NationalityModel $model): Nationality
    {
        return new Nationality(
            new NationalityId($model->id),
            new CountryId($model->country_id),
            $model->name ?? [],
            $model->is_active,
            new DateTimeImmutable($model->created_at->toDateTimeString()),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );
    }

    public function save(Nationality $nationality): void
    {
        NationalityModel::query()->updateOrCreate(
            ['id' => $nationality->id()->value],
            [
                'country_id' => $nationality->countryId()->value,
                'name' => $nationality->names(),
                'is_active' => $nationality->isActive(),
            ]
        );
    }

    public function delete(NationalityId $id): void
    {
        NationalityModel::query()->where('id', $id->value)->delete();
    }
}
