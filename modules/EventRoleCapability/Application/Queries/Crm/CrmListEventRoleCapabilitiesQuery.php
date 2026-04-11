<?php
// modules/EventRoleCapability/Application/Queries/Crm/CrmListEventRoleCapabilitiesQuery.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Application\Queries\Crm;

use Modules\Shared\Domain\ValueObject\FilterCriteria;

final readonly class CrmListEventRoleCapabilitiesQuery
{
    public function __construct(
        public FilterCriteria $criteria,
    ) {}
}
