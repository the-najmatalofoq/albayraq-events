<?php
// modules/EventRoleCapability/Infrastructure/Persistence/EventRoleCapabilityReflector.php
declare(strict_types=1);

namespace Modules\EventRoleCapability\Infrastructure\Persistence;

use Modules\EventRoleCapability\Domain\EventRoleCapability;
use Modules\EventRoleCapability\Domain\ValueObject\CapabilityId;
use Modules\EventRoleAssignment\Domain\ValueObject\AssignmentId;
use Modules\EventRoleCapability\Infrastructure\Persistence\Eloquent\EventRoleCapabilityModel;

final class EventRoleCapabilityReflector
{
    public static function fromModel(EventRoleCapabilityModel $model): EventRoleCapability
    {
        $reflection = new \ReflectionClass(EventRoleCapability::class);
        $capability = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => CapabilityId::fromString($model->id),
            'assignmentId' => AssignmentId::fromString($model->assignment_id),
            'capabilityKey' => $model->capability_key,
            'isGranted' => $model->is_granted,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($capability, $value);
        }

        return $capability;
    }
}
