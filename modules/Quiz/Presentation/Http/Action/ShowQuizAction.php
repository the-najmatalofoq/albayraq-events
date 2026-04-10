<?php
// modules/Quiz/Presentation/Http/Action/ShowQuizAction.php
declare(strict_types=1);

namespace Modules\Quiz\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Quiz\Domain\ValueObject\QuizId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowQuizAction
{
    public function __construct(
        private QuizRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $quiz = $this->repository->findById(QuizId::fromString($id));

        if ($quiz === null || $quiz->eventId->value !== $eventId) {
            return $this->responder->notFound('messages.quiz.not_found');
        }

        return $this->responder->success([
            'id' => $quiz->uuid->value,
            'event_id' => $quiz->eventId->value,
            'title' => $quiz->title->toArray(),
            'description' => $quiz->description?->toArray(),
            'passing_score' => $quiz->passingScore,
            'is_active' => $quiz->isActive,
        ]);
    }
}
