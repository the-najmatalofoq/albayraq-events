<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\Nationality;
use Modules\Geography\Domain\ValueObject\NationalityId;
use Modules\Geography\Domain\ValueObject\CountryId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface NationalityRepositoryInterface
{
    public function nextIdentity(): NationalityId;
    public function findById(NationalityId $id): ?Nationality;
    public function findActiveByCountryId(CountryId $countryId): array;
    public function findAllActive(): array;
    public function save(Nationality $nationality): void;
    public function delete(NationalityId $id): void;
}
