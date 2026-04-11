<?php
// filePath: modules/EventPositionApplication/Application/Commands/Crm/CrmRestoreEventPositionApplicationCommand.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Commands\Crm;

final readonly class CrmRestoreEventPositionApplicationCommand
{
    public function __construct(public string $id) {}
}
