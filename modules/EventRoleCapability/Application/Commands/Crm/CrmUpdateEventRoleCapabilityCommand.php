<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmUpdateEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Crm;

final readonly class CrmUpdateEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
        public string $assignmentId,
        public string $capabilityKey,
        public bool $isGranted,
    ) {}
}
