<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\ToggleJoinRequestStatus\ToggleJoinRequestStatusCommand;
use Modules\User\Application\Command\ToggleJoinRequestStatus\ToggleJoinRequestStatusHandler;

final class ToggleJoinRequestStatusAction
{
    public function __construct(
        private readonly ToggleJoinRequestStatusHandler $handler,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new ToggleJoinRequestStatusCommand(joinRequestId: $id));

        return $this->responder->success(messageKey: 'join_requests.status_toggled');
    }
}
