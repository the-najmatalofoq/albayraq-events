<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmHardDeleteEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardHardDeleteEventPositionApplicationCommand;

final readonly class HardDeleteEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(HardDeleteEventPositionApplicationCommand $command): void
    {
        $this->repository->hardDelete(ApplicationId::fromString($command->id));
    }
}
