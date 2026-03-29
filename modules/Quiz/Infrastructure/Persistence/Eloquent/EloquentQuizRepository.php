<?php

declare(strict_types=1);

namespace Modules\Quiz\Infrastructure\Persistence\Eloquent;

use Modules\Quiz\Domain\Quiz;
use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Quiz\Infrastructure\Persistence\QuizReflector;

final class EloquentQuizRepository implements QuizRepositoryInterface
{
    public function findById(QuizId $id): ?Quiz
    {
        $model = QuizModel::find($id->value);
        if (!$model) {
            return null;
        }

        return QuizReflector::fromModel($model);
    }

    public function save(Quiz $quiz): void
    {
        QuizModel::updateOrCreate(
            ['id' => $quiz->uuid->value],
            [
                'event_id' => $quiz->eventId->value,
                'title' => $quiz->title->toArray(),
                'description' => $quiz->description?->toArray(),
                'passing_score' => $quiz->passingScore,
                'is_active' => $quiz->isActive,
            ],
        );
    }
}
