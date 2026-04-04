<?php
declare(strict_types=1);

namespace Modules\Geography\Infrastructure\Persistence\Eloquent\Repositories;

use Modules\Geography\Domain\Country;
use Modules\Geography\Domain\Repository\CountryRepositoryInterface;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Infrastructure\Persistence\Eloquent\Models\CountryModel;
use DateTimeImmutable;

// fix: use __construct in all the EloquentRepository 
final class EloquentCountryRepository implements CountryRepositoryInterface
{
    public function findById(CountryId $id): ?Country
    {
        $model = CountryModel::find($id->value);
        return $model ? $this->toEntity($model) : null;
    }

    public function findAllActive(): array
    {
        return CountryModel::where('is_active', true)
            ->get()
            ->map(fn(CountryModel $model) => $this->toEntity($model))
            ->toArray();
    }

    private function toEntity(CountryModel $model): Country
    {
        return new Country(
            new CountryId($model->id),
            $model->code,
            $model->name ?? [],
            $model->phone_code,
            $model->is_active,
            new DateTimeImmutable($model->created_at->toDateTimeString()),
            $model->updated_at ? new DateTimeImmutable($model->updated_at->toDateTimeString()) : null
        );
    }
}
