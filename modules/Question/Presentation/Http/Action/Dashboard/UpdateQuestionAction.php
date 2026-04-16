<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmUpdateQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardUpdateQuestionHandler;
use Modules\Question\Application\Commands\Dashboard\DashboardUpdateQuestionCommand;
use Modules\Question\Presentation\Http\Request\Dashboard\DashboardUpdateQuestionRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class UpdateQuestionAction
{
    public function __construct(
        private UpdateQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateQuestionRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(
            new CrmUpdateQuestionCommand(
                id: $id,
                quizId: $request->input('quiz_id'),
                content: $request->input('content'),
                options: $request->input('options', []),
                type: $request->input('type'),
                scoreWeight: (int) $request->input('score_weight', 1)
            )
        );

        return $this->responder->success(messageKey: 'messages.question.updated');
    }
}
