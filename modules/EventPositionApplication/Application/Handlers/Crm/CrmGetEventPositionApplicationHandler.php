<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmGetEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Crm;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Application\Queries\Crm\CrmGetEventPositionApplicationQuery;

final readonly class CrmGetEventPositionApplicationHandler
{
    public function __construct(private EventPositionApplicationRepositoryInterface $repository) {}

    public function handle(CrmGetEventPositionApplicationQuery $query): ?EventPositionApplication
    {
        $id = ApplicationId::fromString($query->id);
        return $query->withTrashed
            ? $this->repository->findByIdWithTrashed($id)
            : $this->repository->findById($id);
    }
}
