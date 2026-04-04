<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\Country;
use Modules\Geography\Domain\ValueObject\CountryId;

interface CountryRepositoryInterface
{
    public function findById(CountryId $id): ?Country;
    public function findAllActive(): array;
}
