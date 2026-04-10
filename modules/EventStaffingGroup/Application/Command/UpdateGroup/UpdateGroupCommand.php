<?php
// modules/EventStaffingGroup/Application/Command/UpdateGroup/UpdateGroupCommand.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Application\Command\UpdateGroup;

final readonly class UpdateGroupCommand
{
    public function __construct(
        public string $id,
        public array $name,
        public string $color,
        public bool $isLocked,
        public ?string $leaderId = null,
    ) {
    }
}
