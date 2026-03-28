<?php
// modules/EmployeeAnswer/Infrastructure/Persistence/EmployeeAnswerReflector.php
declare(strict_types=1);

namespace Modules\EmployeeAnswer\Infrastructure\Persistence;

use Modules\EmployeeAnswer\Domain\EmployeeAnswer;
use Modules\EmployeeAnswer\Domain\ValueObject\AnswerId;
use Modules\EmployeeQuizAttempt\Domain\ValueObject\AttemptId;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\EmployeeAnswer\Infrastructure\Persistence\Eloquent\EmployeeAnswerModel;

final class EmployeeAnswerReflector
{
    public static function fromModel(EmployeeAnswerModel $model): EmployeeAnswer
    {
        $reflection = new \ReflectionClass(EmployeeAnswer::class);
        $answer = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'          => AnswerId::fromString($model->id),
            'attemptId'     => AttemptId::fromString($model->attempt_id),
            'questionId'    => QuestionId::fromString($model->question_id),
            'answerId'      => $model->answer_id,
            'isCorrect'     => (bool) $model->is_correct,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($answer, $value);
        }

        return $answer;
    }
}
