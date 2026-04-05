<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\ApproveJoinRequest\ApproveJoinRequestCommand;
use Modules\User\Application\Command\ApproveJoinRequest\ApproveJoinRequestHandler;
use Modules\User\Presentation\Http\Request\ApproveJoinRequestRequest;

final class ApproveJoinRequestAction
{
    public function __construct(
        private readonly ApproveJoinRequestHandler $handler,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(ApproveJoinRequestRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new ApproveJoinRequestCommand(
            joinRequestId: $id,
            reviewedBy: $request->user()->id,
            notes: (string) $request->validated('notes'),
        ));

        return $this->responder->success(messageKey: 'join_requests.approved');
    }
}
