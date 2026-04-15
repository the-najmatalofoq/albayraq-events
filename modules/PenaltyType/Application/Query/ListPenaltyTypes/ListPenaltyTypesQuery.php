<?php
// modules/PenaltyType/Application/Query/ListPenaltyTypes/ListPenaltyTypesQuery.php
declare(strict_types=1);

namespace Modules\PenaltyType\Application\Query\ListPenaltyTypes;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class ListPenaltyTypesQuery
{
    public function __construct(
        public FilterCriteria $criteria,
        public int $perPage = 15,
        public bool $paginated = false
    ) {}
}
