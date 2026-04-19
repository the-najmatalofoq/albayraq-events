<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmRestoreEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardRestoreEventPositionApplicationCommand;

final readonly class RestoreEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(RestoreEventPositionApplicationCommand $command): void
    {
        $this->repository->restore(ApplicationId::fromString($command->id));
    }
}
