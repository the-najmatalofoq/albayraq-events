<?php
// modules/Quiz/Presentation/Http/Action/CreateQuizAction.php
declare(strict_types=1);

namespace Modules\Quiz\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Quiz\Application\Command\CreateQuiz\CreateQuizCommand;
use Modules\Quiz\Application\Command\CreateQuiz\CreateQuizHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateQuizAction
{
    public function __construct(
        private CreateQuizHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    // fix: make the (CreateQuiz) formRequest for validation

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $id = $this->handler->handle(new CreateQuizCommand(
            eventId: $eventId,
            title: $request->input('title'),
            description: $request->input('description'),
            passingScore: (int) $request->input('passing_score', 80),
        ));

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.quiz.created'
        );
    }
}
