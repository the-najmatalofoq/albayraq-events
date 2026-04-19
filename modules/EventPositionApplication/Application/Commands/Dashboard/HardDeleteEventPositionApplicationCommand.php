<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmHardDeleteEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Dashboard;

final readonly class HardDeleteEventPositionApplicationCommand
{
    public function __construct(public string $id) {}
}
