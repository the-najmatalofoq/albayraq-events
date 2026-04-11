<?php
// modules/EventRoleCapability/Application/Queries/Crm/CrmGetEventRoleCapabilityQuery.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Queries\Crm;

final readonly class CrmGetEventRoleCapabilityQuery
{
    public function __construct(
        public string $id,
        public bool $withIdTrashed = false,
    ) {}
}
