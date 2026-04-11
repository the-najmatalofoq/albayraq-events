<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmRestoreEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Crm;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Application\Commands\Crm\CrmRestoreEventPositionApplicationCommand;

final readonly class CrmRestoreEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(CrmRestoreEventPositionApplicationCommand $command): void
    {
        $this->repository->restore(ApplicationId::fromString($command->id));
    }
}
