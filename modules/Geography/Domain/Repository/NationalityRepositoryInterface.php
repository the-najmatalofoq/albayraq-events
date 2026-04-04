<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\Nationality;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Geography\Domain\ValueObject\CountryId;

interface NationalityRepositoryInterface
{
    public function findById(NationalityId $id): ?Nationality;
    public function findActiveByCountryId(CountryId $countryId): array;
    public function findAllActive(): array;
}
