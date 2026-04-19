<?php
// filePath: modules/Question/Presentation/Http/Presenter/CrmQuestionPresenter.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Presenter;

use Modules\Question\Domain\Question;

final readonly class QuestionPresenter
{
    public function present(Question $question): array
    {
        return [
            'id' => $question->uuid->value,
            'quiz_id' => $question->quizId->value,
            'content' => $question->content->toArray(),
            'type' => $question->type,
            'options' => $question->options,
            'score_weight' => $question->scoreWeight,
            'is_deleted' => $question->isDeleted(),
            'deleted_at' => $question->deletedAt?->format(\DateTimeInterface::ATOM),
        ];
    }

    public function presentCollection(iterable $questions): array
    {
        $result = [];
        foreach ($questions as $question) {
            $result[] = $this->present($question);
        }
        return $result;
    }
}
