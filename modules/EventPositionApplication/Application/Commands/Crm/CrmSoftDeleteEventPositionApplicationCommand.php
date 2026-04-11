<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmSoftDeleteEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Crm;

final readonly class CrmSoftDeleteEventPositionApplicationCommand
{
    public function __construct(public string $id) {}
}
