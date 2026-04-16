<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmShowQuestionAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Question\Application\Handlers\Dashboard\DashboardGetQuestionHandler;
use Modules\Question\Application\Queries\Dashboard\DashboardGetQuestionQuery;
use Modules\Question\Presentation\Http\Presenter\DashboardQuestionPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowQuestionAction
{
    public function __construct(
        private GetQuestionHandler $handler,
        private QuestionPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $question = $this->handler->handle(
            new CrmGetQuestionQuery(
                id: $id,
                withIdTrashed: filter_var($request->query('trashed'), FILTER_VALIDATE_BOOLEAN)
            )
        );

        if (!$question) {
            return $this->responder->notFound('messages.question.not_found');
        }

        return $this->responder->success(
            data: $this->presenter->present($question)
        );
    }
}
