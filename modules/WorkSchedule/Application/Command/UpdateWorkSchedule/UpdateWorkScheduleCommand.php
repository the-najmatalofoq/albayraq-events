<?php
// modules/WorkSchedule/Application/Command/UpdateWorkSchedule/UpdateWorkScheduleCommand.php
declare(strict_types=1);

namespace Modules\WorkSchedule\Application\Command\UpdateWorkSchedule;

final readonly class UpdateWorkScheduleCommand
{
    public function __construct(
        public string $id,
        public ?\DateTimeImmutable $date = null,
        public ?string $startTime = null,
        public ?string $endTime = null,
        public ?bool $isActive = null
    ) {}
}
