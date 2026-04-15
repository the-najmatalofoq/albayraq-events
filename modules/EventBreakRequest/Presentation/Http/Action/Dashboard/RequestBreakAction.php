<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/RequestBreakAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action\Dashboard;

use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Presentation\Http\Requests\RequestBreakRequest;
use Modules\EventBreakRequest\Application\Command\RequestBreak\RequestBreakCommand;
use Modules\EventBreakRequest\Application\Command\RequestBreak\RequestBreakHandler;
use Illuminate\Http\JsonResponse;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;

final readonly class RequestBreakAction
{
    public function __construct(
        private RequestBreakHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(RequestBreakRequest $request, string $eventId): JsonResponse
    {
        try {
            $breakRequestId = $this->handler->handle(new RequestBreakCommand(
                eventId: $eventId,
                participationId: $request->validated('participation_id'),
                requestedByUserId: Auth::id(),
                date: CarbonImmutable::parse($request->validated('date')),
                startTime: CarbonImmutable::parse($request->validated('start_time')),
                endTime: CarbonImmutable::parse($request->validated('end_time'))
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
