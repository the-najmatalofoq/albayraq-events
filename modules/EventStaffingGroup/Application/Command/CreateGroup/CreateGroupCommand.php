<?php
// modules/EventStaffingGroup/Application/Command/CreateGroup/CreateGroupCommand.php
declare(strict_types=1);

namespace Modules\EventStaffingGroup\Application\Command\CreateGroup;

final readonly class CreateGroupCommand
{
    public function __construct(
        public string $eventId,
        public array $name,
        public string $color,
        public bool $isLocked = false,
        public ?string $leaderId = null,
    ) {
    }
}
