<?php
// modules/Quiz/Presentation/Http/Action/ListQuizzesAction.php
declare(strict_types=1);

namespace Modules\Quiz\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Quiz\Domain\Repository\QuizRepositoryInterface;
use Modules\Event\Domain\ValueObject\EventId;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListQuizzesAction
{
    public function __construct(
        private QuizRepositoryInterface $repository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId): JsonResponse
    {
        $quizzes = $this->repository->listByEventId(EventId::fromString($eventId));

        return $this->responder->success(
            data: array_map(fn($q) => [
                'id' => $q->uuid->value,
                'title' => $q->title->toArray(),
                'description' => $q->description?->toArray(),
                'passing_score' => $q->passingScore,
                'is_active' => $q->isActive,
            ], $quizzes)
        );
    }
}
