<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmUpdateQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Crm\CrmUpdateQuestionHandler;
use Modules\Question\Application\Commands\Crm\CrmUpdateQuestionCommand;
use Modules\Question\Presentation\Http\Request\Crm\CrmUpdateQuestionRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmUpdateQuestionAction
{
    public function __construct(
        private CrmUpdateQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmUpdateQuestionRequest $request, string $id): JsonResponse
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
