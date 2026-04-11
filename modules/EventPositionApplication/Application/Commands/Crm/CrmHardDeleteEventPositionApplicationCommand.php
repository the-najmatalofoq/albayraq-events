<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmHardDeleteEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Crm;

final readonly class CrmHardDeleteEventPositionApplicationCommand
{
    public function __construct(public string $id) {}
}
