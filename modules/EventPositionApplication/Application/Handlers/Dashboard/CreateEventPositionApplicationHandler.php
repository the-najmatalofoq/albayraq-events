<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmCreateEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardCreateEventPositionApplicationCommand;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationStatusEnum;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final readonly class CreateEventPositionApplicationHandler
{
    public function __construct(
        private EventPositionApplicationRepositoryInterface $repository,
    ) {}

    public function handle(CreateEventPositionApplicationCommand $command): ApplicationId
    {
        $id = $this->repository->nextIdentity();

        $application = EventPositionApplication::create(
            uuid: $id,
            userId: UserId::fromString($command->userId),
            positionId: PositionId::fromString($command->positionId),
            status: ApplicationStatusEnum::from($command->status),
            rankingScore: $command->rankingScore,
        );

        $this->repository->save($application);
        return $id;
    }
}
