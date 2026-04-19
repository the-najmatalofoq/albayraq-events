<?php
// filePath: modules/EventPositionApplication/Application/Handlers/Crm/CrmUpdateEventPositionApplicationHandler.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Application\Handlers\Dashboard;

use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Application\Commands\Dashboard\DashboardUpdateEventPositionApplicationCommand;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationStatusEnum;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;

final readonly class UpdateEventPositionApplicationHandler
{
    public function __construct(
        private EventPositionApplicationRepositoryInterface $repository,
    ) {}

    public function handle(UpdateEventPositionApplicationCommand $command): void
    {
        $existing = $this->repository->findByIdWithTrashed(ApplicationId::fromString($command->id));
        if (!$existing) {
            throw new \DomainException("Application {$command->id} not found.");
        }

        $updated = EventPositionApplication::reconstitute(
            uuid: $existing->uuid,
            userId: UserId::fromString($command->userId),
            positionId: PositionId::fromString($command->positionId),
            status: ApplicationStatusEnum::from($command->status),
            rankingScore: $command->rankingScore,
            appliedAt: $existing->appliedAt,
            reviewedAt: $existing->reviewedAt,
            reviewedBy: $existing->reviewedBy,
            deletedAt: $existing->deletedAt,
        );

        $this->repository->save($updated);
    }
}
