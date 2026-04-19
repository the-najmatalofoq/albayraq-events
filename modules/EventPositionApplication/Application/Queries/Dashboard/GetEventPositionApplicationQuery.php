<?php
// filePath: modules/EventPositionApplication/Application/Queries/Crm/CrmGetEventPositionApplicationQuery.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Queries\Dashboard;

final readonly class GetEventPositionApplicationQuery
{
    public function __construct(
        public string $id,
        public bool $withTrashed = false,
    ) {}
}
