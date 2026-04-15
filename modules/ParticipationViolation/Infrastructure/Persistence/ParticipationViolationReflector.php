<?php
// modules/ParticipationViolation/Infrastructure/Persistence/ParticipationViolationReflector.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Infrastructure\Persistence;

use Modules\ParticipationViolation\Domain\ParticipationViolation;
use Modules\ParticipationViolation\Domain\ValueObject\ViolationId;
use Modules\ViolationType\Domain\ValueObject\ViolationTypeId;
use Modules\DeductionType\Domain\ValueObject\DeductionTypeId;
use Modules\PenaltyType\Domain\ValueObject\PenaltyTypeId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationViolation\Infrastructure\Persistence\Eloquent\ParticipationViolationModel;
use DateTimeImmutable;

final class ParticipationViolationReflector
{
    public static function fromModel(ParticipationViolationModel $model): ParticipationViolation
    {
        $reflection = new \ReflectionClass(ParticipationViolation::class);
        $violation = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => ViolationId::fromString($model->id),
            'participationId'   => ParticipationId::fromString($model->event_participation_id),
            'violationTypeId'   => ViolationTypeId::fromString($model->violation_type_id),
            'deductionTypeId'   => $model->deduction_type_id ? DeductionTypeId::fromString($model->deduction_type_id) : null,
            'penaltyTypeId'     => $model->penalty_type_id ? PenaltyTypeId::fromString($model->penalty_type_id) : null,
            'reportedBy'        => UserId::fromString($model->reported_by),
            'description'       => $model->description,
            'date'              => DateTimeImmutable::createFromInterface($model->date),
            'currentTier'       => (int) $model->current_tier,
            'status'            => $model->status,
            'deductionAmount'   => $model->deduction_amount ? (float) $model->deduction_amount : null,
            'createdAt'         => $model->created_at->toDateTimeImmutable(),
            'approvedBy'        => $model->approved_by ? UserId::fromString($model->approved_by) : null,
            'approvedAt'        => $model->approved_at ? DateTimeImmutable::createFromInterface($model->approved_at) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($violation, $value);
        }

        return $violation;
    }
}
