<?php
// modules/DeductionType/Application/Query/ListDeductionTypes/ListDeductionTypesQuery.php
declare(strict_types=1);

namespace Modules\DeductionType\Application\Query\ListDeductionTypes;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class ListDeductionTypesQuery
{
    public function __construct(
        public FilterCriteria $criteria,
        public int $perPage = 15,
        public bool $paginated = false
    ) {}
}
