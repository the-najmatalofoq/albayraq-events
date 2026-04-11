<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmHardDeleteEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Crm;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmHardDeleteEventPositionApplicationCommand;

final readonly class CrmHardDeleteEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(CrmHardDeleteEventPositionApplicationCommand $command): void
    {
        $this->repository->hardDelete(ApplicationId::fromString($command->id));
    }
}
