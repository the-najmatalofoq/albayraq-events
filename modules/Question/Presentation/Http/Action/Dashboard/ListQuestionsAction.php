<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmListQuestionsAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardListQuestionsHandler;
use Modules\Question\Application\Queries\Dashboard\DashboardListQuestionsQuery;
use Modules\Question\Presentation\Http\Request\Dashboard\DashboardListQuestionsRequest;
use Modules\Question\Presentation\Http\Presenter\DashboardQuestionPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListQuestionsAction
{
    public function __construct(
        private ListQuestionsHandler $handler,
        private QuestionPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ListQuestionsRequest $request): JsonResponse
    {
        $questions = $this->handler->handle(
            new CrmListQuestionsQuery($request->toFilterCriteria())
        );

        return $this->responder->success(
            data: $this->presenter->presentCollection($questions)
        );
    }
}
