<?php
// modules/EventBreakRequest/Application/Queries/GetEmployeeRemainingBreakTimeQuery.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Application\Query;

final readonly class GetEmployeeRemainingBreakTimeQuery
{
    public function __construct(
        public string $participationId,
        public string $date
    ) {}
}
