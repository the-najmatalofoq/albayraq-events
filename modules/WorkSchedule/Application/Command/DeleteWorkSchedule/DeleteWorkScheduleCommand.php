<?php
// modules/WorkSchedule/Application/Command/DeleteWorkSchedule/DeleteWorkScheduleCommand.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Command\DeleteWorkSchedule;

final readonly class DeleteWorkScheduleCommand
{
    public function __construct(
        public string $id
    ) {}
}
