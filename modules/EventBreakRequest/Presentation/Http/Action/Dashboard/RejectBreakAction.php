<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/RejectBreakAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action\Dashboard;

use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Presentation\Http\Requests\RejectBreakRequest;
use Modules\EventBreakRequest\Application\Command\RejectBreak\RejectBreakCommand;
use Modules\EventBreakRequest\Application\Command\RejectBreak\RejectBreakHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final readonly class RejectBreakAction
{
    public function __construct(
        private RejectBreakHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(RejectBreakRequest $request, string $eventId, string $id): JsonResponse
    {
        try {
            $this->handler->handle(new RejectBreakCommand(
                breakRequestId: $id,
                rejectorId: Auth::id(),
                reason: $request->validated('rejection_reason')
            ));

            return $this->responder->success(
                messageKey: 'break_requests.messages.request_rejected'
            );
        } catch (\DomainException $e) {
            return $this->responder->error('DOMAIN_EXCEPTION', 400, $e->getMessage());
        }
    }
}
