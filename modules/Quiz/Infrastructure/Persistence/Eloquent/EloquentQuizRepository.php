<?php

declare(strict_types=1);

namespace Modules\Quiz\Infrastructure\Persistence\Eloquent;

use Modules\Event\Domain\ValueObject\EventId;
use Modules\Quiz\Domain\Quiz;
use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Quiz\Infrastructure\Persistence\QuizReflector;

final class EloquentQuizRepository implements QuizRepositoryInterface
{
    public function nextIdentity(): QuizId
    {
        return QuizId::generate();
    }

    public function findById(QuizId $id): ?Quiz
    {
        $model = QuizModel::find($id->value);
        if (!$model) {
            return null;
        }

        return QuizReflector::fromModel($model);
    }

    public function listByEventId(EventId $eventId): array
    {
        return QuizModel::where('event_id', $eventId->value)
            ->get()
            ->map(fn(QuizModel $m) => QuizReflector::fromModel($m))
            ->toArray();
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

    public function delete(QuizId $id): void
    {
        QuizModel::where('id', $id->value)->delete();
    }
}
