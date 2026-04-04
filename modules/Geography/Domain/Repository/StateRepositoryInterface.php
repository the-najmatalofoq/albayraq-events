<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\State;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Domain\ValueObject\CountryId;

interface StateRepositoryInterface
{
    public function findById(StateId $id): ?State;
    public function findByCountryId(CountryId $countryId): array;
}
