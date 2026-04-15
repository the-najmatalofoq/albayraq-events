<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/Mobile/GetEmployeeRemainingBreakTimeAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Application\Query\GetEmployeeRemainingBreakTimeQuery;
use Modules\EventBreakRequest\Application\Query\GetEmployeeRemainingBreakTimeHandler;
use Carbon\Carbon;

final readonly class GetEmployeeRemainingBreakTimeAction
{
    public function __construct(
        private GetEmployeeRemainingBreakTimeHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $participationId = $request->query('participation_id');
        if (!$participationId) {
            return $this->responder->error('MISSING_DATA', 400, 'Participation ID is required');
        }

        $date = Carbon::today()->format('Y-m-d');

        $remaining = $this->handler->handle(new GetEmployeeRemainingBreakTimeQuery(
            participationId: $participationId,
            date: $date
        ));

        return $this->responder->success(data: ['remaining_minutes' => $remaining]);
    }
}
