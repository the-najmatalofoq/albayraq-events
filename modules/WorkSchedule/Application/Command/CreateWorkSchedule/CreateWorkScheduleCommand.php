<?php
// modules/WorkSchedule/Application/Command/CreateWorkSchedule/CreateWorkScheduleCommand.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Command\CreateWorkSchedule;

final readonly class CreateWorkScheduleCommand
{
    public function __construct(
        public string $schedulableId,
        public string $schedulableType,
        public \DateTimeImmutable $date,
        public string $startTime,
        public string $endTime,
        public bool $isActive = true
    ) {}
}
