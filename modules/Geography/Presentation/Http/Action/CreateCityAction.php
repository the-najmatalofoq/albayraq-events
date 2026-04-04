<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\CreateCityRequest;
use Modules\Geography\Application\Command\CreateCity\CreateCityCommand;
use Modules\Geography\Application\Command\CreateCity\CreateCityHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class CreateCityAction
{
    public function __construct(
        private readonly CreateCityHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(CreateCityRequest $request): JsonResponse
    {
        $this->handler->handle(new CreateCityCommand($request->validated()));
        return $this->responder->success(message: 'City created successfully.');
    }
}
