<?php
// modules/Quiz/Infrastructure/Persistence/QuizReflector.php
declare(strict_types=1);

namespace Modules\Quiz\Infrastructure\Persistence;

use Modules\Quiz\Domain\Quiz;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Quiz\Infrastructure\Persistence\Eloquent\QuizModel;

final class QuizReflector
{
    public static function fromModel(QuizModel $model): Quiz
    {
        $reflection = new \ReflectionClass(Quiz::class);
        $quiz = $reflection->newInstanceWithoutConstructor();

        $properties = [
            'uuid'          => QuizId::fromString($model->id),
            'eventId'       => EventId::fromString($model->event_id),
            'title'         => TranslatableText::fromArray($model->title),
            'description'   => $model->description ? TranslatableText::fromArray($model->description) : null,
            'passingScore'  => (int) $model->passing_score,
            'isActive'      => (bool) $model->is_active,
        ];

        foreach ($properties as $field => $value) {
            $prop = $reflection->getProperty($field);
            $prop->setValue($quiz, $value);
        }

        return $quiz;
    }
}
