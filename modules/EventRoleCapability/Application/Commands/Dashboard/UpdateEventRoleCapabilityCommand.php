<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmUpdateEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Dashboard;

final readonly class UpdateEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
        public string $assignmentId,
        public string $capabilityKey,
        public bool $isGranted,
    ) {}
}
