<?php
// modules/ParticipationEvaluation/Presentation/Http/Presenter/ParticipationEvaluationPresenter.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Presentation\Http\Presenter;

use Modules\ParticipationEvaluation\Domain\ParticipationEvaluation;

final class ParticipationEvaluationPresenter
{
    public function present(ParticipationEvaluation $evaluation): array
    {
        return [
            'uuid'              => $evaluation->uuid->value,
            'participation_id'   => $evaluation->participationId->value,
            'evaluator_id'       => $evaluation->evaluatorId->value,
            'date'              => $evaluation->date->format('Y-m-d'),
            'score'             => $evaluation->score,
            'notes'             => $evaluation->notes,
            'is_locked'         => $evaluation->isLocked,
            'locked_at'         => $evaluation->lockedAt?->format(DATE_ATOM),
            'created_at'        => $evaluation->createdAt->format(DATE_ATOM),
        ];
    }

    public function presentCollection(iterable $evaluations): array
    {
        $data = [];
        foreach ($evaluations as $evaluation) {
            $data[] = $this->present($evaluation);
        }
        return $data;
    }
}
