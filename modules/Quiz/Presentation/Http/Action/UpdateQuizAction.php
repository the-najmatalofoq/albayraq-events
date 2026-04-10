<?php
// modules/Quiz/Presentation/Http/Action/UpdateQuizAction.php
declare(strict_types=1);

namespace Modules\Quiz\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Quiz\Application\Command\UpdateQuiz\UpdateQuizCommand;
use Modules\Quiz\Application\Command\UpdateQuiz\UpdateQuizHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateQuizAction
{
    public function __construct(
        private UpdateQuizHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $eventId, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateQuizCommand(
            id: $id,
            title: $request->input('title'),
            description: $request->input('description'),
            passingScore: (int) $request->input('passing_score'),
        ));

        return $this->responder->success(
            messageKey: 'messages.quiz.updated'
        );
    }
}
