<?php
// modules/Quiz/Application/Command/UpdateQuiz/UpdateQuizHandler.php
declare(strict_types=1);

namespace Modules\Quiz\Application\Command\UpdateQuiz;

use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class UpdateQuizHandler
{
    public function __construct(
        private QuizRepositoryInterface $repository,
    ) {
    }

    public function handle(UpdateQuizCommand $command): void
    {
        $quiz = $this->repository->findById(QuizId::fromString($command->id));

        if ($quiz === null) {
            throw new \DomainException("Quiz {$command->id} not found.");
        }

        $quiz->update(
            title: TranslatableText::fromArray($command->title),
            description: $command->description ? TranslatableText::fromArray($command->description) : null,
            passingScore: $command->passingScore,
        );

        $this->repository->save($quiz);
    }
}
