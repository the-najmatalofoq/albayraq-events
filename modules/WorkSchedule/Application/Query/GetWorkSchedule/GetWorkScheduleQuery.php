<?php
// modules/WorkSchedule/Application/Query/GetWorkSchedule/GetWorkScheduleQuery.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Query\GetWorkSchedule;

final readonly class GetWorkScheduleQuery
{
    public function __construct(
        public string $id
    ) {}
}
