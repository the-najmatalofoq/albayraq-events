<?php
declare(strict_types=1);

namespace Modules\Geography\Domain\Repository;

use Modules\Geography\Domain\State;
use Modules\Geography\Domain\ValueObject\StateId;
use Modules\Geography\Domain\ValueObject\CountryId;

interface StateRepositoryInterface
{
    public function nextIdentity(): StateId;
    public function findById(StateId $id): ?State;
    public function findByCountryId(CountryId $countryId): array;
    public function save(State $state): void;
    public function delete(StateId $id): void;
}
