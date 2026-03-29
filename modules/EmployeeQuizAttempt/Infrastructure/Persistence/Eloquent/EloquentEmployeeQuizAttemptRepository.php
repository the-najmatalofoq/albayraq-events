<?php

declare(strict_types=1);

namespace Modules\EmployeeQuizAttempt\Infrastructure\Persistence\Eloquent;

use Modules\EmployeeQuizAttempt\Domain\EmployeeQuizAttempt;
use Modules\EmployeeQuizAttempt\Domain\Repository\EmployeeQuizAttemptRepositoryInterface;
use Modules\EmployeeQuizAttempt\Domain\ValueObject\AttemptId;
use Modules\EmployeeQuizAttempt\Infrastructure\Persistence\EmployeeQuizAttemptReflector;

final class EloquentEmployeeQuizAttemptRepository implements EmployeeQuizAttemptRepositoryInterface
{
    public function findById(AttemptId $id): ?EmployeeQuizAttempt
    {
        $model = EmployeeQuizAttemptModel::find($id->value);
        if (!$model) {
            return null;
        }

        return EmployeeQuizAttemptReflector::fromModel($model);
    }

    public function save(EmployeeQuizAttempt $attempt): void
    {
        EmployeeQuizAttemptModel::updateOrCreate(
            ['id' => $attempt->uuid->value],
            [
                'quiz_id' => $attempt->quizId->value,
                'event_participation_id' => $attempt->participationId->value,
                'score' => $attempt->score,
                'status' => $attempt->status,
                'started_at' => $attempt->startedAt->format('Y-m-d H:i:s'),
                'completed_at' => $attempt->completedAt?->format('Y-m-d H:i:s'),
            ],
        );
    }
}
