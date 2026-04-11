<?php
// filePath: modules/Question/Application/Handlers/Crm/CrmCreateQuestionHandler.php
declare(strict_types=1);

namespace Modules\Question\Application\Handlers\Crm;

use Modules\Question\Domain\Repository\QuestionRepositoryInterface;
use Modules\Question\Domain\Question;
use Modules\Question\Application\Commands\Crm\CrmCreateQuestionCommand;
use Modules\Question\Domain\ValueObject\QuestionId;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CrmCreateQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $repository,
    ) {}

    public function handle(CrmCreateQuestionCommand $command): QuestionId
    {
        $id = $this->repository->nextIdentity();
        
        $question = Question::create(
            uuid: $id,
            quizId: QuizId::fromString($command->quizId),
            content: TranslatableText::fromArray($command->content),
            options: $command->options,
            type: $command->type,
            scoreWeight: $command->scoreWeight
        );

        $this->repository->save($question);

        return $id;
    }
}
