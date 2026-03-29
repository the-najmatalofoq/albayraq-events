<?php

declare(strict_types=1);

namespace Modules\EmployeeAnswer\Infrastructure\Persistence\Eloquent;

use Modules\EmployeeAnswer\Domain\EmployeeAnswer;
use Modules\EmployeeAnswer\Domain\Repository\EmployeeAnswerRepositoryInterface;
use Modules\EmployeeAnswer\Domain\ValueObject\AnswerId;
use Modules\EmployeeAnswer\Infrastructure\Persistence\EmployeeAnswerReflector;

final class EloquentEmployeeAnswerRepository implements EmployeeAnswerRepositoryInterface
{
    public function findById(AnswerId $id): ?EmployeeAnswer
    {
        $model = EmployeeAnswerModel::find($id->value);
        if (!$model) {
            return null;
        }

        return EmployeeAnswerReflector::fromModel($model);
    }

    public function save(EmployeeAnswer $answer): void
    {
        EmployeeAnswerModel::updateOrCreate(
            ['id' => $answer->uuid->value],
            [
                'attempt_id' => $answer->attemptId->value,
                'question_id' => $answer->questionId->value,
                'answer_id' => $answer->answerId,
                'is_correct' => $answer->isCorrect,
            ],
        );
    }
}
