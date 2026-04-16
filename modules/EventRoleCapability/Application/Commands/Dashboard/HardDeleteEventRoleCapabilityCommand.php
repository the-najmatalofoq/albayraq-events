<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmHardDeleteEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Dashboard;

final readonly class HardDeleteEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
    ) {}
}
