<?php
// modules/EventRoleCapability/Application/Commands/Crm/CrmRestoreEventRoleCapabilityCommand.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Commands\Crm;

final readonly class CrmRestoreEventRoleCapabilityCommand
{
    public function __construct(
        public string $id,
    ) {}
}
