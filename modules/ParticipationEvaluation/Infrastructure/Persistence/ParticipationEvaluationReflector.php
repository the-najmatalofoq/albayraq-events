<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/ParticipationEvaluationReflector.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Infrastructure\Persistence;

use Modules\ParticipationEvaluation\Domain\ParticipationEvaluation;
use Modules\ParticipationEvaluation\Domain\ValueObject\EvaluationId;
use Modules\User\Domain\ValueObject\UserId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent\ParticipationEvaluationModel;
use DateTimeImmutable;

final class ParticipationEvaluationReflector
{
    public static function fromModel(ParticipationEvaluationModel $model): ParticipationEvaluation
    {
        $reflection = new \ReflectionClass(ParticipationEvaluation::class);
        $evaluation = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => EvaluationId::fromString($model->id),
            'participationId'   => ParticipationId::fromString($model->event_participation_id),
            'evaluatorId'       => UserId::fromString($model->evaluator_id),
            'date'              => DateTimeImmutable::createFromInterface($model->date),
            'score'             => (float) $model->score,
            'notes'             => $model->notes,
            'isLocked'          => (bool) $model->is_locked,
            'lockedAt'          => $model->locked_at ? DateTimeImmutable::createFromInterface($model->locked_at) : null,
            'createdAt'         => $model->created_at->toDateTimeImmutable(),
            'updatedAt'         => $model->updated_at?->toDateTimeImmutable(),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($evaluation, $value);
        }

        return $evaluation;
    }
}
