<?php
declare(strict_types=1);

namespace Modules\Geography\Presentation\Http\Action;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Geography\Application\Command\DeleteCity\DeleteCityCommand;
use Modules\Geography\Application\Command\DeleteCity\DeleteCityHandler;
use Modules\Shared\Presentation\Http\JsonResponder;

final class DeleteCityAction
{
    public function __construct(
        private readonly DeleteCityHandler $handler,
        private readonly JsonResponder $responder
    ) {}

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $this->handler->handle(new DeleteCityCommand($id));
        return $this->responder->success(message: 'City deleted successfully.');
    }
}
