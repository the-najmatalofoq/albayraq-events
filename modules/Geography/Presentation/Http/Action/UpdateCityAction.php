<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\UpdateCityRequest;
use Modules\Geography\Application\Command\UpdateCity\UpdateCityCommand;
use Modules\Geography\Application\Command\UpdateCity\UpdateCityHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class UpdateCityAction
{
    public function __construct(
        private readonly UpdateCityHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(UpdateCityRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateCityCommand($id, $request->validated()));
        return $this->responder->success(message: 'City updated successfully.');
    }
}
