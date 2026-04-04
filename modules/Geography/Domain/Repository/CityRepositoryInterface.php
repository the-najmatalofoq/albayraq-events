<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\City;
use Modules\Geography\Domain\ValueObject\CityId;
use Modules\Geography\Domain\ValueObject\CountryId;
use Modules\Geography\Domain\ValueObject\StateId;

interface CityRepositoryInterface
{
    public function findById(CityId $id): ?City;
    public function findByCountryId(CountryId $countryId): array;
    public function findByStateId(StateId $stateId): array;
}
