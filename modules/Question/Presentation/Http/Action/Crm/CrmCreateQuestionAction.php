<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmCreateQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Crm\CrmCreateQuestionHandler;
use Modules\Question\Application\Commands\Crm\CrmCreateQuestionCommand;
use Modules\Question\Presentation\Http\Request\Crm\CrmCreateQuestionRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmCreateQuestionAction
{
    public function __construct(
        private CrmCreateQuestionHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmCreateQuestionRequest $request): JsonResponse
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
