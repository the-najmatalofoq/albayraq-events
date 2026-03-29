<?php

declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence\Eloquent;

use Modules\Question\Domain\Question;
use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Question\Infrastructure\Persistence\QuestionReflector;

final class EloquentQuestionRepository implements QuestionRepositoryInterface
{
    public function findById(QuestionId $id): ?Question
    {
        $model = QuestionModel::find($id->value);
        if (!$model) {
            return null;
        }

        return QuestionReflector::fromModel($model);
    }

    public function save(Question $question): void
    {
        QuestionModel::updateOrCreate(
            ['id' => $question->uuid->value],
            [
                'quiz_id' => $question->quizId->value,
                'content' => $question->content->toArray(),
                'type' => $question->type,
                'options' => $question->options,
                'score_weight' => $question->scoreWeight,
            ],
        );
    }
}
