<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/ListBreakRequestsAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action\Dashboard;

use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Application\Query\GetPendingBreakRequestsQuery;
use Modules\EventBreakRequest\Application\Query\GetPendingBreakRequestsHandler;
use Modules\EventBreakRequest\Presentation\Http\Resources\BreakRequestPresenter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final readonly class ListBreakRequestsAction
{
    public function __construct(
        private GetPendingBreakRequestsHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request, string $eventId): JsonResponse
    {
        $date = $request->query('date');

        $requests = $this->handler->handle(new GetPendingBreakRequestsQuery(
            eventId: $eventId,
            date: $date
        ));

        // Let's assume we map it using a presenter
        $data = $requests->map(fn($req) => BreakRequestPresenter::fromModel($req))->toArray();

        return $this->responder->success(data: $data);
    }
}
