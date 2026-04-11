<?php
// modules/Question/Infrastructure/Persistence/QuestionReflector.php
declare(strict_types=1);

namespace Modules\Question\Infrastructure\Persistence;

use Modules\Question\Domain\Question;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Question\Infrastructure\Persistence\Eloquent\QuestionModel;

final class QuestionReflector
{
    public static function fromModel(QuestionModel $model): Question
    {
        $reflection = new \ReflectionClass(Question::class);
        $question = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'          => QuestionId::fromString($model->id),
            'quizId'        => QuizId::fromString($model->quiz_id),
            'content'       => TranslatableText::fromArray($model->content),
            'type'          => $model->type,
            'options'       => $model->options,
            'scoreWeight'   => (int) $model->score_weight,
            'deletedAt'     => $model->deleted_at ? new \DateTimeImmutable($model->deleted_at->toDateTimeString()) : null,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($question, $value);
        }

        return $question;
    }
}
