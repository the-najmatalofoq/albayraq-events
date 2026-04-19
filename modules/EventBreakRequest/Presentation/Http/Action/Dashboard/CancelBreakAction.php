<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/CancelBreakAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action\Dashboard;

use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Application\Command\CancelBreak\CancelBreakCommand;
use Modules\EventBreakRequest\Application\Command\CancelBreak\CancelBreakHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final readonly class CancelBreakAction
{
    public function __construct(
        private CancelBreakHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(string $eventId, string $id): JsonResponse
    {
        try {
            $this->handler->handle(new CancelBreakCommand(
                breakRequestId: $id,
                requestedByUserId: Auth::id()
            ));

            return $this->responder->success(
                messageKey: 'break_requests.messages.request_cancelled'
            );
        } catch (\DomainException $e) {
            return $this->responder->error('DOMAIN_EXCEPTION', 400, $e->getMessage());
        }
    }
}
