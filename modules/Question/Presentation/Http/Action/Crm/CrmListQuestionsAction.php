<?php
// filePath: modules/Question/Presentation/Http/Action/Crm/CrmListQuestionsAction.php
declare(strict_types=1);

namespace Modules\Question\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\Question\Application\Handlers\Crm\CrmListQuestionsHandler;
use Modules\Question\Application\Queries\Crm\CrmListQuestionsQuery;
use Modules\Question\Presentation\Http\Request\Crm\CrmListQuestionsRequest;
use Modules\Question\Presentation\Http\Presenter\CrmQuestionPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListQuestionsAction
{
    public function __construct(
        private CrmListQuestionsHandler $handler,
        private CrmQuestionPresenter $presenter,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(CrmListQuestionsRequest $request): JsonResponse
    {
        $questions = $this->handler->handle(
            new CrmListQuestionsQuery($request->toFilterCriteria())
        );

        return $this->responder->success(
            data: $this->presenter->presentCollection($questions)
        );
    }
}
