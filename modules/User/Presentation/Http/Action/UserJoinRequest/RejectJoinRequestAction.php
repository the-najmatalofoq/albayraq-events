<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\UserJoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\IAM\Domain\Service\TokenManager;
use Modules\User\Application\Command\RejectJoinRequest\RejectJoinRequestCommand;
use Modules\User\Application\Command\RejectJoinRequest\RejectJoinRequestHandler;
use Modules\User\Presentation\Http\Request\RejectJoinRequestRequest;
use Modules\Shared\Presentation\Http\JsonResponder;

final readonly class RejectJoinRequestAction
{
    public function __construct(
        private TokenManager $tokenManager,
        private RejectJoinRequestHandler $handler,
        private JsonResponder $responder,
    ) {}

    public function __invoke(RejectJoinRequestRequest $request, string $id): JsonResponse
    {
        $userId = $this->tokenManager->getUserIdFromToken();

        if ($userId === null) {
            return $this->responder->unauthorized();
        }

        $this->handler->handle(new RejectJoinRequestCommand(
            requestId: $id,
            reviewedBy: $userId->value,
            notes: (string) $request->validated('notes')
        ));

        return $this->responder->success(
            messageKey: 'user.join_request_rejected'
        );
    }
}
