<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/Mobile/QuickRequestBreakAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Application\Command\RequestBreak\RequestBreakCommand;
use Modules\EventBreakRequest\Application\Command\RequestBreak\RequestBreakHandler;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

final readonly class QuickRequestBreakAction
{
    public function __construct(
        private RequestBreakHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $eventId = $request->input('event_id');
        $participationId = $request->input('participation_id');
        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        if (!$eventId || !$participationId || !$date || !$startTime || !$endTime) {
            return $this->responder->error('MISSING_DATA', 400, 'All fields are required for quick request');
        }

        try {
            $breakRequestId = $this->handler->handle(new RequestBreakCommand(
                eventId: $eventId,
                participationId: $participationId,
                requestedByUserId: Auth::id(),
                date: CarbonImmutable::parse($date),
                startTime: CarbonImmutable::parse($startTime),
                endTime: CarbonImmutable::parse($endTime)
            ));

            return $this->responder->created(
                data: ['id' => $breakRequestId],
                messageKey: 'break_requests.messages.request_created'
            );
        } catch (\DomainException $e) {
            return $this->responder->error('DOMAIN_EXCEPTION', 400, $e->getMessage());
        }
    }
}
