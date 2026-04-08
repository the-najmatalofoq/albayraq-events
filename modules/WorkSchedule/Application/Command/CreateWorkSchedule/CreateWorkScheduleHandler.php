<?php
// modules/WorkSchedule/Application/Command/CreateWorkSchedule/CreateWorkScheduleHandler.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Command\CreateWorkSchedule;

use Modules\WorkSchedule\Domain\WorkSchedule;
use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final readonly class CreateWorkScheduleHandler
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository
    ) {}

    public function handle(CreateWorkScheduleCommand $command): ScheduleId
    {
        $id = $this->repository->nextIdentity();

        $workSchedule = WorkSchedule::create(
            uuid: $id,
            schedulableId: $command->schedulableId,
            schedulableType: $command->schedulableType,
            date: $command->date,
            startTime: $command->startTime,
            endTime: $command->endTime,
            isActive: $command->isActive
        );

        $this->repository->save($workSchedule);

        return $id;
    }
}
