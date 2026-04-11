<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmCreateEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Crm;

final readonly class CrmCreateEventRoleCapabilityCommand
{
    public function __construct(
        public string $assignmentId,
        public string $capabilityKey,
        public bool $isGranted = true,
    ) {}
}
