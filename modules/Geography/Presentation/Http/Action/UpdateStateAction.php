<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\UpdateStateRequest;
use Modules\Geography\Application\Command\UpdateState\UpdateStateCommand;
use Modules\Geography\Application\Command\UpdateState\UpdateStateHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class UpdateStateAction
{
    public function __construct(
        private readonly UpdateStateHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(UpdateStateRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateStateCommand($id, $request->validated()));
        return $this->responder->success(message: 'State updated successfully.');
    }
}
