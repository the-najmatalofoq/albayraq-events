<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\CreateStateRequest;
use Modules\Geography\Application\Command\CreateState\CreateStateCommand;
use Modules\Geography\Application\Command\CreateState\CreateStateHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class CreateStateAction
{
    public function __construct(
        private readonly CreateStateHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(CreateStateRequest $request): JsonResponse
    {
        $this->handler->handle(new CreateStateCommand($request->validated()));
        return $this->responder->success(message: 'State created successfully.');
    }
}
