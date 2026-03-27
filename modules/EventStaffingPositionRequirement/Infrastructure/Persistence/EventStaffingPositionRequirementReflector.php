<?php
// modules/EventStaffingPositionRequirement/Infrastructure/Persistence/EventStaffingPositionRequirementReflector.php
declare(strict_types=1);

namespace Modules\EventStaffingPositionRequirement\Infrastructure\Persistence;

use Modules\EventStaffingPositionRequirement\Domain\EventStaffingPositionRequirement;
use Modules\EventStaffingPositionRequirement\Domain\ValueObject\RequirementId;
use Modules\EventStaffingPosition\Domain\ValueObject\PositionId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\EventStaffingPositionRequirement\Infrastructure\Persistence\Eloquent\EventStaffingPositionRequirementModel;

final class EventStaffingPositionRequirementReflector
{
    public static function fromModel(EventStaffingPositionRequirementModel $model): EventStaffingPositionRequirement
    {
        $reflection = new \ReflectionClass(EventStaffingPositionRequirement::class);
        $requirement = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => RequirementId::fromString($model->id),
            'positionId' => PositionId::fromString($model->position_id),
            'title' => TranslatableText::fromArray($model->title),
            'isRequired' => $model->is_required,
            'description' => $model->description,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($requirement, $value);
        }

        return $requirement;
    }
}
