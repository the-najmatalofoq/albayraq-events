<?php
// modules/EmployeeQuizAttempt/Infrastructure/Persistence/EmployeeQuizAttemptReflector.php
declare(strict_types=1);

namespace Modules\EmployeeQuizAttempt\Infrastructure\Persistence;

use Modules\EmployeeQuizAttempt\Domain\EmployeeQuizAttempt;
use Modules\EmployeeQuizAttempt\Domain\ValueObject\AttemptId;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\EventParticipation\Domain\ValueObject\ParticipationId;
use Modules\EmployeeQuizAttempt\Infrastructure\Persistence\Eloquent\EmployeeQuizAttemptModel;
use DateTimeImmutable;

final class EmployeeQuizAttemptReflector
{
    public static function fromModel(EmployeeQuizAttemptModel $model): EmployeeQuizAttempt
    {
        $reflection = new \ReflectionClass(EmployeeQuizAttempt::class);
        $attempt = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'              => AttemptId::fromString($model->id),
            'quizId'            => QuizId::fromString($model->quiz_id),
            'participationId'   => ParticipationId::fromString($model->event_participation_id),
            'score'             => (int) $model->score,
            'status'            => $model->status,
            'startedAt'         => DateTimeImmutable::createFromInterface($model->started_at),
            'completedAt'       => $model->completed_at ? DateTimeImmutable::createFromInterface($model->completed_at) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($attempt, $value);
        }

        return $attempt;
    }
}
