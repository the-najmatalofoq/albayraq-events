<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\CreateCountryRequest;
use Modules\Geography\Application\Command\CreateCountry\CreateCountryCommand;
use Modules\Geography\Application\Command\CreateCountry\CreateCountryHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class CreateCountryAction
{
    public function __construct(
        private readonly CreateCountryHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(CreateCountryRequest $request): JsonResponse
    {
        $this->handler->handle(new CreateCountryCommand($request->validated()));
        return $this->responder->success(message: 'Country created successfully.');
    }
}
