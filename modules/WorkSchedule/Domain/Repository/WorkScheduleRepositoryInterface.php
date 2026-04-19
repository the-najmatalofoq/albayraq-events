<?php
// modules/WorkSchedule/Domain/Repository/WorkScheduleRepositoryInterface.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Domain\Repository;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\Shared\Domain\ValueObject\ScheduleId;
use Modules\Shared\Domain\Repository\FilterableRepositoryInterface;

interface WorkScheduleRepositoryInterface extends FilterableRepositoryInterface
{
    public function nextIdentity(): ScheduleId;

    public function save(WorkSchedule $workSchedule): void;

    public function findById(ScheduleId $id): ?WorkSchedule;

    public function delete(ScheduleId $id): void;
}
