<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmCreateQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardCreateQuestionHandler;
use Modules\Question\Application\Commands\Dashboard\DashboardCreateQuestionCommand;
use Modules\Question\Presentation\Http\Request\Dashboard\DashboardCreateQuestionRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CreateQuestionAction
{
    public function __construct(
        private CreateQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CreateQuestionRequest $request): JsonResponse
    {
        $id = $this->handler->handle(
            new CrmCreateQuestionCommand(
                quizId: $request->input('quiz_id'),
                content: $request->input('content'),
                options: $request->input('options', []),
                type: $request->input('type'),
                scoreWeight: (int) $request->input('score_weight', 1)
            )
        );

        return $this->responder->created(
            data: ['id' => $id->value],
            messageKey: 'messages.question.created'
        );
    }
}
