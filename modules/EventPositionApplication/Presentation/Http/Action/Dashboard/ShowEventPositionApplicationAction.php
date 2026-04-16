<?php
// filePath: modules/EventPositionApplication/Presentation/Http/Action/Crm/CrmShowEventPositionApplicationAction.php
declare(strict_types=1);

namespace Modules\EventPositionApplication\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventPositionApplication\Application\Handlers\Dashboard\DashboardGetEventPositionApplicationHandler;
use Modules\EventPositionApplication\Application\Queries\Dashboard\DashboardGetEventPositionApplicationQuery;
use Modules\EventPositionApplication\Presentation\Http\Presenter\DashboardEventPositionApplicationPresenter;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ShowEventPositionApplicationAction
{
    public function __construct(
        private GetEventPositionApplicationHandler $handler,
        private EventPositionApplicationPresenter $presenter,
        private JsonResponder $responder,
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $item = $this->handler->handle(new CrmGetEventPositionApplicationQuery(
            id: $id,
            withTrashed: filter_var($request->query('trashed'), FILTER_VALIDATE_BOOLEAN)
        ));
        if (!$item) {
            return $this->responder->notFound('messages.application.not_found');
        }
        return $this->responder->success(data: $this->presenter->present($item));
    }
}
