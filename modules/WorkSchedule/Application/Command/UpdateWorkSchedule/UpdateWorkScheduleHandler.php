<?php
// modules/WorkSchedule/Application/Command/UpdateWorkSchedule/UpdateWorkScheduleHandler.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Command\UpdateWorkSchedule;

use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final readonly class UpdateWorkScheduleHandler
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository
    ) {}

    public function handle(UpdateWorkScheduleCommand $command): void
    {
        $id = ScheduleId::fromString($command->id);
        $workSchedule = $this->repository->findById($id);

        if (!$workSchedule) {
            // throw Not Found Exception
            return;
        }

        $workSchedule->update(
            date: $command->date,
            startTime: $command->startTime,
            endTime: $command->endTime,
            isActive: $command->isActive
        );

        $this->repository->save($workSchedule);
    }
}
