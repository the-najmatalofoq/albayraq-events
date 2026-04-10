<?php
// modules/Quiz/Application/Command/CreateQuiz/CreateQuizHandler.php
declare(strict_types=1);

namespace Modules\Quiz\Application\Command\CreateQuiz;

use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Domain\Quiz;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Domain\ValueObject\TranslatableText;

final readonly class CreateQuizHandler
{
    public function __construct(
        private QuizRepositoryInterface $repository,
    ) {
    }

    public function handle(CreateQuizCommand $command): QuizId
    {
        $id = $this->repository->nextIdentity();
        
        $quiz = Quiz::create(
            uuid: $id,
            eventId: EventId::fromString($command->eventId),
            title: TranslatableText::fromArray($command->title),
            description: $command->description ? TranslatableText::fromArray($command->description) : null,
            passingScore: $command->passingScore,
        );

        $this->repository->save($quiz);

        return $id;
    }
}
