<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmListPaginatedQuestionsAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Dashboard\DashboardListPaginatedQuestionsHandler;
use Modules\Question\Application\Queries\Dashboard\DashboardListPaginatedQuestionsQuery;
use Modules\Question\Infrastructure\Persistence\QuestionReflector;
use Modules\Question\Presentation\Http\Request\Dashboard\DashboardListQuestionsRequest;
use Modules\Question\Presentation\Http\Presenter\DashboardQuestionPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ListPaginatedQuestionsAction
{
    public function __construct(
        private ListPaginatedQuestionsHandler $handler,
        private QuestionPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(ListQuestionsRequest $request): JsonResponse
    {
        $paginator = $this->handler->handle(
            new CrmListPaginatedQuestionsQuery(
                $request->toFilterCriteria(),
                $request->toPaginationCriteria()
            )
        );

        return $this->responder->paginated(
            items: $paginator->items(),
            total: $paginator->total(),
            pagination: $request->toPaginationCriteria(),
            presenter: fn($item) => $this->presenter->present(QuestionReflector::fromModel($item))
        );
    }
}
