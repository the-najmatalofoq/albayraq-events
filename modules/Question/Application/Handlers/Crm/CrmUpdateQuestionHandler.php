<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmUpdateQuestionHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Crm;

use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\Question;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Shared\Domain\ValueObject\TranslatableText;
use Modules\Question\Application\Commands\Crm\CrmUpdateQuestionCommand;

final readonly class CrmUpdateQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(CrmUpdateQuestionCommand $command): void
    {
        $question = $this->repository->findByIdWithTrashed(QuestionId::fromString($command->id));

        if (!$question) {
            throw new \DomainException("Question {$command->id} not found.");
        }

        $updatedQuestion = Question::reconstitute(
            uuid: $question->uuid,
            quizId: QuizId::fromString($command->quizId),
            content: TranslatableText::fromArray($command->content),
            type: $command->type,
            options: $command->options,
            scoreWeight: $command->scoreWeight,
            deletedAt: $question->deletedAt
        );

        $this->repository->save($updatedQuestion);
    }
}
