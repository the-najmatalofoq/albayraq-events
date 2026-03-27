<?php
// modules/ParticipationEvaluation/Presentation/Http/Presenter/ParticipationEvaluationPresenter.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Presentation\Http\Presenter;

use Modules\ParticipationEvaluation\Domain\ParticipationEvaluation;

final class ParticipationEvaluationPresenter
{
    public static function fromDomain(ParticipationEvaluation $evaluation): array
    {
        return [
            'id' => $evaluation->uuid->value,
            'event_participation_id' => $evaluation->participationId->value,
            'rating' => $evaluation->rating,
            'feedback' => $evaluation->feedback?->toArray(),
            'evaluated_by' => $evaluation->evaluatedBy->value,
            'created_at' => $evaluation->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
