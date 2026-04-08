<?php
// modules/ViolationType/Application/Query/ListViolationTypes/ListViolationTypesQuery.php
declare(strict_types=1);

namespace Modules\ViolationType\Application\Query\ListViolationTypes;

use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final readonly class ListViolationTypesQuery
{
    public function __construct(
        public PaginationCriteria $pagination,
        public ?string $search = null
    ) {}
}
