<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmSoftDeleteEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Dashboard;

final readonly class SoftDeleteEventPositionApplicationCommand
{
    public function __construct(public string $id) {}
}
