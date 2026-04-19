<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmSoftDeleteEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardSoftDeleteEventPositionApplicationCommand;

final readonly class SoftDeleteEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(SoftDeleteEventPositionApplicationCommand $command): void
    {
        $this->repository->delete(ApplicationId::fromString($command->id));
    }
}
