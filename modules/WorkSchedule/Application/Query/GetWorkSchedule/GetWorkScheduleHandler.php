<?php
// modules/WorkSchedule/Application/Query/GetWorkSchedule/GetWorkScheduleHandler.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Query\GetWorkSchedule;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final readonly class GetWorkScheduleHandler
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository
    ) {}

    public function handle(GetWorkScheduleQuery $query): ?WorkSchedule
    {
        $id = ScheduleId::fromString($query->id);
        return $this->repository->findById($id);
    }
}
