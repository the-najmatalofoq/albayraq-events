<?php
// modules/WorkSchedule/Application/Command/DeleteWorkSchedule/DeleteWorkScheduleHandler.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Command\DeleteWorkSchedule;

use Modules\WorkSchedule\Domain\Repository\WorkScheduleRepositoryInterface;
use Modules\Shared\Domain\ValueObject\ScheduleId;

final readonly class DeleteWorkScheduleHandler
{
    public function __construct(
        private WorkScheduleRepositoryInterface $repository
    ) {}

    public function handle(DeleteWorkScheduleCommand $command): void
    {
        $id = ScheduleId::fromString($command->id);
        $this->repository->delete($id);
    }
}
