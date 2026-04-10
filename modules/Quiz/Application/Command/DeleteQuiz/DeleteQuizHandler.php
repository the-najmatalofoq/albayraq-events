<?php
// modules/Quiz/Application/Command/DeleteQuiz/DeleteQuizHandler.php
declare(strict_types=1);

namespace Modules\Quiz\Application\Command\DeleteQuiz;

use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Domain\ValueObject\QuizId;

final readonly class DeleteQuizHandler
{
    public function __construct(
        private QuizRepositoryInterface $repository,
    ) {
    }

    public function handle(string $id): void
    {
        $this->repository->delete(QuizId::fromString($id));
    }
}
