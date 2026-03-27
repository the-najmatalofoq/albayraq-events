<?php
// modules/EventRoleCapability/Infrastructure/Persistence/Eloquent/EloquentEventRoleCapabilityRepository.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent;

use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleCapability\Domain\Repository\EventRoleCapabilityRepositoryInterface;
use Modules\EventRoleCapability\Infrastructure\Persistence\EventRoleCapabilityReflector;

final class EloquentEventRoleCapabilityRepository implements EventRoleCapabilityRepositoryInterface
{
    public function nextIdentity(): CapabilityId
    {
        return CapabilityId::generate();
    }

    public function save(EventRoleCapability $capability): void
    {
        EventRoleCapabilityModel::updateOrCreate(
            ['id' => $capability->uuid->value],
            [
                'assignment_id' => $capability->assignmentId->value,
                'capability_key' => $capability->capabilityKey,
                'is_granted' => $capability->isGranted,
            ]
        );
    }

    public function findById(CapabilityId $id): ?EventRoleCapability
    {
        $model = EventRoleCapabilityModel::find($id->value);
        return $model ? EventRoleCapabilityReflector::fromModel($model) : null;
    }

    public function findByAssignmentId(AssignmentId $assignmentId): array
    {
        return EventRoleCapabilityModel::where('assignment_id', $assignmentId->value)
            ->get()
            ->map(function (EventRoleCapabilityModel $model) {
                return EventRoleCapabilityReflector::fromModel($model);
            })
            ->toArray();
    }
}
