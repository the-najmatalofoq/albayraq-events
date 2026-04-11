<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmListPaginatedQuestionsAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Crm\CrmListPaginatedQuestionsHandler;
use Modules\Question\Application\Queries\Crm\CrmListPaginatedQuestionsQuery;
use Modules\Question\Infrastructure\Persistence\QuestionReflector;
use Modules\Question\Presentation\Http\Request\Crm\CrmListQuestionsRequest;
use Modules\Question\Presentation\Http\Presenter\CrmQuestionPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListPaginatedQuestionsAction
{
    public function __construct(
        private CrmListPaginatedQuestionsHandler $handler,
        private CrmQuestionPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmListQuestionsRequest $request): JsonResponse
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
