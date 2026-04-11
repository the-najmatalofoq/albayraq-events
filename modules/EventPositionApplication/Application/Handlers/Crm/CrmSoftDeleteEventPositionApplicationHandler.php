<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmSoftDeleteEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Crm;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmSoftDeleteEventPositionApplicationCommand;

final readonly class CrmSoftDeleteEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(CrmSoftDeleteEventPositionApplicationCommand $command): void
    {
        $this->repository->delete(ApplicationId::fromString($command->id));
    }
}
