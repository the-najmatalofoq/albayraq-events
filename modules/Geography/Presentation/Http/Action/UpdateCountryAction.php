<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Modules\Geography\Presentation\Http\Request\UpdateCountryRequest;
use Modules\Geography\Application\Command\UpdateCountry\UpdateCountryCommand;
use Modules\Geography\Application\Command\UpdateCountry\UpdateCountryHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class UpdateCountryAction
{
    public function __construct(
        private readonly UpdateCountryHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(UpdateCountryRequest $request, string $id): JsonResponse
    {
        $this->handler->handle(new UpdateCountryCommand($id, $request->validated()));
        return $this->responder->success(message: 'Country updated successfully.');
    }
}
