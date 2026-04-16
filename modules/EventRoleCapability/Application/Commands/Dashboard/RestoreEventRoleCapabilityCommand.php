<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmRestoreEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Dashboard;

final readonly class RestoreEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
    ) {}
}
