<?php
// modules/WorkSchedule/Application/Query/ListWorkSchedules/ListWorkSchedulesHandler.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Query\ListWorkSchedules;

use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;

final readonly class ListWorkSchedulesHandler
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository
    ) {}

    public function handle(ListWorkSchedulesQuery $query): array
    {
        return $this->repository->paginate(
            $query->pagination,
            $query->schedulableType,
            $query->schedulableId
        );
    }
}
