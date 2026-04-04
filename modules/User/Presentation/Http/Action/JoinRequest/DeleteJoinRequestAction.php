<?php

declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\JoinRequest;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Application\Command\DeleteJoinRequest\DeleteJoinRequestCommand;
use Modules\User\Application\Command\DeleteJoinRequest\DeleteJoinRequestHandler;

final class DeleteJoinRequestAction
{
    public function __construct(
        private readonly DeleteJoinRequestHandler $handler,
        private readonly JsonResponder $responder,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->handler->handle(new DeleteJoinRequestCommand(joinRequestId: $id));

        return $this->responder->noContent();
    }
}
