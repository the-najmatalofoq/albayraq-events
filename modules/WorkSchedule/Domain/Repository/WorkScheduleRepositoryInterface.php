<?php
// modules/WorkSchedule/Domain/Repository/WorkScheduleRepositoryInterface.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Domain\Repository;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\Shared\Domain\ValueObject\ScheduleId;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;

interface WorkScheduleRepositoryInterface
{
    public function nextIdentity(): ScheduleId;

    public function save(WorkSchedule $workSchedule): void;

    public function findById(ScheduleId $id): ?WorkSchedule;

    /**
     * @return array{items: WorkSchedule[], total: int}
     */
    public function paginate(
        PaginationCriteria $criteria,
        ?string $schedulableType = null,
        ?string $schedulableId = null
    ): array;

    public function delete(ScheduleId $id): void;
}
