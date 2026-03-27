<?php
// modules/EventPositionApplication/Infrastructure/Persistence/Eloquent/EloquentEventPositionApplicationRepository.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Infrastructure\Persistence\Eloquent;

use Modules\EventPositionApplication\Domain\EventPositionApplication;
use Modules\EventPositionApplication\Domain\ValueObject\ApplicationId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventPositionApplication\Domain\Repository\EventPositionApplicationRepositoryInterface;
use Modules\EventPositionApplication\Infrastructure\Persistence\EventPositionApplicationReflector;

final class EloquentEventPositionApplicationRepository implements EventPositionApplicationRepositoryInterface
{
    public function nextIdentity(): ApplicationId
    {
        return ApplicationId::generate();
    }

    public function save(EventPositionApplication $application): void
    {
        EventPositionApplicationModel::updateOrCreate(
            ['id' => $application->uuid->value],
            [
                'user_id' => $application->userId->value,
                'position_id' => $application->positionId->value,
                'status' => $application->status->value,
                'ranking_score' => $application->rankingScore,
                'applied_at' => $application->appliedAt->format('Y-m-d H:i:s'),
                'reviewed_at' => $application->reviewedAt?->format('Y-m-d H:i:s'),
                'reviewed_by' => $application->reviewedBy?->value,
            ]
        );
    }

    public function findById(ApplicationId $id): ?EventPositionApplication
    {
        $model = EventPositionApplicationModel::find($id->value);
        return $model ? EventPositionApplicationReflector::fromModel($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        return EventPositionApplicationModel::where('user_id', $userId->value)
            ->get()
            ->map(function (EventPositionApplicationModel $model) {
                return EventPositionApplicationReflector::fromModel($model);
            })
            ->toArray();
    }

    public function findByPositionId(PositionId $positionId): array
    {
        return EventPositionApplicationModel::where('position_id', $positionId->value)
            ->get()
            ->map(function (EventPositionApplicationModel $model) {
                return EventPositionApplicationReflector::fromModel($model);
            })
            ->toArray();
    }
}
