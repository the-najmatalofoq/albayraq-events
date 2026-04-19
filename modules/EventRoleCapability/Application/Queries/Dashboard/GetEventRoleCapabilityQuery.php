<?php
// modules/EventRoleCapability/Application/Queries/Crm/CrmGetEventRoleCapabilityQuery.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Queries\Dashboard;

final readonly class GetEventRoleCapabilityQuery
{
    public function __construct(
        public string $id,
        public bool $withIdTrashed = false,
    ) {}
}
