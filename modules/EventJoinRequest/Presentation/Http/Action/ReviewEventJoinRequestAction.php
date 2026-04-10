<?php
// modules/EventJoinRequest/Presentation/Http/Action/ReviewEventJoinRequestAction.php
declare(strict_types=1);

namespace Modules\EventJoinRequest\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\EventJoinRequest\Application\Command\ReviewJoinRequest\ReviewJoinRequestCommand;
use Modules\EventJoinRequest\Application\Command\ReviewJoinRequest\ReviewJoinRequestHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class ReviewEventJoinRequestAction
{
    public function __construct(
        private ReviewJoinRequestHandler $handler,
        private JsonResponder $responder,
    ) {
    }
    // fix: make (ReviewEventJoinRequestAction) FormRequest for validation.
    public function __invoke(Request $request, string $eventId, string $id): JsonResponse
    {
        $this->handler->handle(new ReviewJoinRequestCommand(
            joinRequestId: $id,
            reviewerId: (string) $request->user()->id,
            approved: (bool) $request->input('approved'),
            rejectionReason: $request->input('rejection_reason'),
        ));

        return $this->responder->success(messageKey: 'messages.join_request.reviewed');
    }
}
