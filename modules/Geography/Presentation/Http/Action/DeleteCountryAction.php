<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Geography\Application\Command\DeleteCountry\DeleteCountryCommand;
use Modules\Geography\Application\Command\DeleteCountry\DeleteCountryHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class DeleteCountryAction
{
    public function __construct(
        private readonly DeleteCountryHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->handler->handle(new DeleteCountryCommand($id));
        return $this->responder->success(message: 'Country deleted successfully.');
    }
}
