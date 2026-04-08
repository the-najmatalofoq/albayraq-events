<?php
// modules/WorkSchedule/Application/Query/ListWorkSchedules/ListWorkSchedulesQuery.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Query\ListWorkSchedules;

use Modules\Shared\Domain\ValueObject\PaginationCriteria;

final readonly class ListWorkSchedulesQuery
{
    public function __construct(
        public PaginationCriteria $pagination,
        public ?string $schedulableType = null,
        public ?string $schedulableId = null
    ) {}
}
