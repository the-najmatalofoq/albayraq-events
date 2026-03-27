<?php
// modules/ParticipationEvaluation/Infrastructure/Persistence/ParticipationEvaluationReflector.php
declare(strict_types=1);

namespace Modules\ParticipationEvaluation\Infrastructure\Persistence;

use Modules\ParticipationEvaluation\Domain\ParticipationEvaluation;
use Modules\ParticipationEvaluation\Domain\ValueObject\EvaluationId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\IAM\Domain\ValueObject\UserId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\ParticipationEvaluation\Infrastructure\Persistence\Eloquent\ParticipationEvaluationModel;

final class ParticipationEvaluationReflector
{
    public static function fromModel(ParticipationEvaluationModel $model): ParticipationEvaluation
    {
        $reflection = new \ReflectionClass(ParticipationEvaluation::class);
        $evaluation = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid' => EvaluationId::fromString($model->id),
            'participationId' => ParticipationId::fromString($model->event_participation_id),
            'rating' => $model->rating,
            'feedback' => $model->feedback ? TranslatableText::fromArray($model->feedback) : null,
            'evaluatedBy' => UserId::fromString($model->evaluated_by),
            'createdAt' => \DateTimeImmutable::createFromMutable($model->created_at),
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($evaluation, $value);
        }

        return $evaluation;
    }
}
