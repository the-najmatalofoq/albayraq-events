<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmRestoreEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Dashboard;

final readonly class RestoreEventPositionApplicationCommand
{
    public function __construct(public string $id) {}
}
