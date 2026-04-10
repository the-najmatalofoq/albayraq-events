<?php
// modules/Quiz/Presentation/Http/Action/DeleteQuizAction.php
declare(strict_types=1);

namespace Modules\Quiz\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Quiz\Application\Command\DeleteQuiz\DeleteQuizHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class DeleteQuizAction
{
    public function __construct(
        private DeleteQuizHandler $handler,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        $this->handler->handle($id);

        return $this->responder->noContent();
    }
}
