<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmHardDeleteEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Crm;

final readonly class CrmHardDeleteEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
    ) {}
}
