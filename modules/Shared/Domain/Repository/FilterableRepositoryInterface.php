<?php
declare(strict_types=1);

namespace Modules\Shared\Domain\Repository;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Shared\Domain\ValueObject\FilterCriteria;

interface FilterableRepositoryInterface
{
    public function paginate(FilterCriteria $criteria, int $perPage = 15): LengthAwarePaginator;
    public function all(FilterCriteria $criteria): Collection;
}
