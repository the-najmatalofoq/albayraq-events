<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\Country;
use Modules\Geography\Domain\ValueObject\CountryId;
// fix: use the fiter in the listAll also.

// fix: use the FilterableRepositoryInterface
interface CountryRepositoryInterface
{
    public function nextIdentity(): CountryId;
    public function findById(CountryId $id): ?Country;
    public function findAllActive(): array;
    public function save(Country $country): void;
    public function delete(CountryId $id): void;
}
