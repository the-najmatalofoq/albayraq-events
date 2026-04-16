<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmSoftDeleteEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Dashboard;

final readonly class SoftDeleteEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
    ) {}
}
