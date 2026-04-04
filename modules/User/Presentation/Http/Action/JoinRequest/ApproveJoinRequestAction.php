<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\ApproveJoinRequest\ApproveJoinRequestCommand;
use Modules\User\Application\Command\ApproveJoinRequest\ApproveJoinRequestHandler;

final class ApproveJoinRequestAction
{
    public function __construct(
        private readonly ApproveJoinRequestHandler $handler,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        // fix: make the ApproveJoinRequestRquest file for the notes.
        $this->handler->handle(new ApproveJoinRequestCommand(
            joinRequestId: $id,
            reviewedBy: $request->user()->id,
            notes: $request->input('notes'),
        ));

        return $this->responder->success(messageKey: 'join_requests.approved');
    }
}
