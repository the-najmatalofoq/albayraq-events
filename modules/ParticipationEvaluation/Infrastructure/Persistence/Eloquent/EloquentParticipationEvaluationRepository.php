<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/Eloquent/EloquentParticipationEvaluationRepository.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent;

use Modules\ParticipationEvaluation\Domain\ParticipationEvaluation;
use Modules\ParticipationEvaluation\Domain\ValueObject\EvaluationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationEvaluation\Domain\Repository\ParticipationEvaluationRepositoryInterface;
use Modules\ParticipationEvaluation\Infrastructure\Persistence\ParticipationEvaluationReflector;

final class EloquentParticipationEvaluationRepository implements ParticipationEvaluationRepositoryInterface
{
    public function nextIdentity(): EvaluationId
    {
        return EvaluationId::generate();
    }

    public function save(ParticipationEvaluation $evaluation): void
    {
        ParticipationEvaluationModel::updateOrCreate(
            ['id' => $evaluation->uuid->value],
            [
                'event_participation_id' => $evaluation->participationId->value,
                'rating' => $evaluation->rating,
                'feedback' => $evaluation->feedback?->toArray(),
                'evaluated_by' => $evaluation->evaluatedBy->value,
            ]
        );
    }

    public function findById(EvaluationId $id): ?ParticipationEvaluation
    {
        $model = ParticipationEvaluationModel::find($id->value);
        return $model ? ParticipationEvaluationReflector::fromModel($model) : null;
    }

    public function findByParticipationId(ParticipationId $participationId): ?ParticipationEvaluation
    {
        $model = ParticipationEvaluationModel::where('event_participation_id', $participationId->value)->first();
        return $model ? ParticipationEvaluationReflector::fromModel($model) : null;
    }
}
