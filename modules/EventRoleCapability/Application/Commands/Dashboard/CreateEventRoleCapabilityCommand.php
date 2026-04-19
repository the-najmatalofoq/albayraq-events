<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmCreateEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Dashboard;

final readonly class CreateEventRoleCapabilityCommand
{
    public function __construct(
        public string $assignmentId,
        public string $capabilityKey,
        public bool $isGranted = true,
    ) {}
}
