<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\UserUpdateRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\ReviewUpdateRequest\ReviewUpdateRequestCommand;
use Modules\User\Application\Command\ReviewUpdateRequest\ReviewUpdateRequestHandler;
use Modules\User\Presentation\Http\Request\UserUpdateRequest\ReviewUserUpdateRequest;

final class ReviewUserUpdateAction
{
    public function __construct(
        private readonly ReviewUpdateRequestHandler $handler,
        private readonly JsonResponder $responder,
    ) {}

    public function __invoke(ReviewUserUpdateRequest $request, string $id): JsonResponse
    {
        $command = new ReviewUpdateRequestCommand(
            requestId: $id,
            adminId: auth()->id,
            action: $request->validated('action'),
            rejectionReason: $request->validated('rejection_reason'),
        );

        $entity = $this->handler->handle($command);

        $messageKey = $entity->status->value === 'approved'
            ? 'messages.user_update_requests.approved'
            : 'messages.user_update_requests.rejected';

        return $this->responder->success(
            data: ['id' => $entity->id()->value, 'status' => $entity->status->value],
            messageKey: $messageKey
        );
    }
}
