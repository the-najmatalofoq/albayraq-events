<?php
// modules/ParticipationViolation/Presentation/Http/Presenter/ParticipationViolationPresenter.php
declare(strict_types=1);

namespace Modules\ParticipationViolation\Presentation\Http\Presenter;

use Modules\ParticipationViolation\Domain\ParticipationViolation;

final class ParticipationViolationPresenter
{
    public static function fromDomain(ParticipationViolation $violation): array
    {
        return [
            'id'                     => $violation->uuid->value,
            'event_participation_id' => $violation->participationId->value,
            'violation_type_id'      => $violation->violationTypeId->value,
            'deduction_type_id'      => $violation->deductionTypeId?->value,
            'penalty_type_id'        => $violation->penaltyTypeId?->value,
            'description'            => $violation->description,
            'reported_by'            => $violation->reportedBy->value,
            'date'                   => $violation->date->format('Y-m-d'),
            'status'                 => $violation->status->value,
            'deduction_amount'       => $violation->deductionAmount,
            'created_at'             => $violation->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
