<?php
// modules/EventBreakRequest/Presentation/Http/Controllers/ApproveBreakAction.php
declare(strict_types=1);

namespace Modules\EventBreakRequest\Presentation\Http\Action\Dashboard;

use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\EventBreakRequest\Presentation\Http\Requests\ApproveBreakRequest;
use Modules\EventBreakRequest\Application\Command\ApproveBreak\ApproveBreakCommand;
use Modules\EventBreakRequest\Application\Command\ApproveBreak\ApproveBreakHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final readonly class ApproveBreakAction
{
    public function __construct(
        private ApproveBreakHandler $handler,
        private JsonResponder $responder
    ) {}

    public function __invoke(ApproveBreakRequest $request, string $eventId, string $id): JsonResponse
    {
        try {
            $this->handler->handle(new ApproveBreakCommand(
                breakRequestId: $id,
                approverId: Auth::id(),
                coverEmployeeId: $request->validated('cover_employee_id')
            ));

            return $this->responder->success(
                messageKey: 'break_requests.messages.request_approved'
            );
        } catch (\DomainException $e) {
            return $this->responder->error('DOMAIN_EXCEPTION', 400, $e->getMessage());
        }
    }
}
