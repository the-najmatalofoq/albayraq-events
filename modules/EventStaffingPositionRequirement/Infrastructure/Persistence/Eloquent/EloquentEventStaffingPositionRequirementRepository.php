<?php
// modules/EventStaffingPositionRequirement/Infrastructure/Persistence/Eloquent/EloquentEventStaffingPositionRequirementRepository.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Infrastructure\Persistence\Eloquent;

use Modules\EventStaffingPositionRequirement\Domain\EventStaffingPositionRequirement;
use Modules\EventStaffingPositionRequirement\Domain\ValueObject\RequirementId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\EventStaffingPositionRequirement\Domain\Repository\EventStaffingPositionRequirementRepositoryInterface;
use Modules\EventStaffingPositionRequirement\Infrastructure\Persistence\EventStaffingPositionRequirementReflector;

final class EloquentEventStaffingPositionRequirementRepository implements EventStaffingPositionRequirementRepositoryInterface
{
    public function nextIdentity(): RequirementId
    {
        return RequirementId::generate();
    }

    public function save(EventStaffingPositionRequirement $requirement): void
    {
        EventStaffingPositionRequirementModel::updateOrCreate(
            ['id' => $requirement->uuid->value],
            [
                'position_id' => $requirement->positionId->value,
                'title' => $requirement->title->toArray(),
                'is_required' => $requirement->isRequired,
                'description' => $requirement->description,
            ]
        );
    }

    public function findById(RequirementId $id): ?EventStaffingPositionRequirement
    {
        $model = EventStaffingPositionRequirementModel::find($id->value);
        return $model ? EventStaffingPositionRequirementReflector::fromModel($model) : null;
    }

    public function findByPositionId(PositionId $positionId): array
    {
        return EventStaffingPositionRequirementModel::where('position_id', $positionId->value)
            ->get()
            ->map(function (EventStaffingPositionRequirementModel $model) {
                return EventStaffingPositionRequirementReflector::fromModel($model);
            })
            ->toArray();
    }
}
