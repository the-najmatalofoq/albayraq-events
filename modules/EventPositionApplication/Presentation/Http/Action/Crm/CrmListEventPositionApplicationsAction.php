<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmListEventPositionApplicationsAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Crm;

use Illuminate\Http\JsonResponse;
use Modules\EventPositionApplication\Application\Handlers\Crm\CrmListEventPositionApplicationsHandler;
use Modules\EventPositionApplication\Application\Queries\Crm\CrmListEventPositionApplicationsQuery;
use Modules\EventPositionApplication\Presentation\Http\Request\Crm\CrmListEventPositionApplicationsRequest;
use Modules\EventPositionApplication\Presentation\Http\Presenter\CrmEventPositionApplicationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class CrmListEventPositionApplicationsAction
{
    public function __construct(
        private CrmListEventPositionApplicationsHandler $handler,
        private CrmEventPositionApplicationPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(CrmListEventPositionApplicationsRequest $request): JsonResponse
    {
        $items = $this->handler->handle(new CrmListEventPositionApplicationsQuery($request->toFilterCriteria()));
        return $this->responder->success(data: $this->presenter->presentCollection($items));
    }
}
